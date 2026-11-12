<?php

namespace App\Services\Office;

use App\Models\Employee\NominatedEmployee;
use App\Models\Event\Event;
use App\Models\Event\EventSchedule;
use App\Models\RSP\vwEmployee;
use App\Models\RSP\xPersonal;
use App\Models\User;
use App\Notifications\EmployeesNominatedNotification;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class EmployeeService
{

public function create(?array $validated, Authenticatable $user)
{
    $employees = $validated['employee'];

    // 0. Kunin muna ang employee data para magamit ang pangalan sa error messages
    $controlNos = array_column($employees, 'control_no');

    $employeeData = vwEmployee::whereIn('ControlNo', $controlNos)
        ->get()
        ->keyBy('ControlNo');

    // pangalan ng employee; control_no lang ang fallback kung wala talaga sa view
    $nameOf = fn ($controlNo) => $employeeData->get($controlNo)?->name ?? $controlNo;

    // 1. Employee na wala sa vwEmployee
    foreach ($employees as $entry) {
        if (!$employeeData->has($entry['control_no'])) {
            throw new \Exception("Employee with control_no {$entry['control_no']} not found.", 404);
        }
    }

    // 2. Duplicates sa mismong request
    $seen = [];
    foreach ($employees as $entry) {
        $key = $entry['event_schedule_id'] . '-' . $entry['control_no'];
        if (isset($seen[$key])) {
            throw new \Exception(
                "{$nameOf($entry['control_no'])} was submitted more than once for this schedule.",
                422
            );
        }
        $seen[$key] = true;
    }

    // 3. Duplicates na nasa database (bulk check)
    $existing = NominatedEmployee::where(function ($query) use ($employees) {
        foreach ($employees as $entry) {
            $query->orWhere(function ($q) use ($entry) {
                $q->where('event_schedule_id', $entry['event_schedule_id'])
                    ->where('control_no', $entry['control_no']);
            });
        }
    })->get(['event_schedule_id', 'control_no', 'full_name']);

    if ($existing->isNotEmpty()) {
        $names = $existing
            ->map(fn ($e) => $e->full_name ?? $nameOf($e->control_no))
            ->unique()
            ->implode(', ');

        throw new \Exception("Already nominated for this schedule: {$names}.", 422);
    }

    // 4. Insert sa loob ng transaction
    return DB::transaction(function () use ($employees, $employeeData, $user) {
        $nominees = [];

        foreach ($employees as $entry) {
            $data = $employeeData->get($entry['control_no']);

            $nominees[] = NominatedEmployee::create([
                'event_id'          => $entry['event_id'],
                'control_no'        => $entry['control_no'],
                'designation'       => $data->position ?? null,
                'status'            => $data->status ?? null,
                'full_name'         => $data->name ?? null,
                'sg'                => $data->sg ?? null,
                'level'             => $data->level ?? null,
                'office'            => $user->office ?? null,
                'nominate_reason'   => $entry['nominate_reason'] ?? null,
                'event_schedule_id' => $entry['event_schedule_id'],
                'nominate_status'   => 'Pending',
            ]);
        }

        $this->notifyHrAdmins($nominees, $user);

        return $nominees;
    });
}
    // public function create(?array $validated, Authenticatable $user)
    // {
    //     $employees = $validated['employee'];

    //     // 1. Check for duplicates within the submitted request itself
    //     $seen = [];
    //     foreach ($employees as $entry) {
    //         $key = $entry['event_schedule_id'] . '-' . $entry['control_no'];
    //         if (isset($seen[$key])) {
    //             throw new \Exception(
    //                 "Duplicate entry in request: control_no {$entry['control_no']} for event_schedule_id {$entry['event_schedule_id']} was submitted more than once.",
    //                 422
    //             );
    //         }
    //         $seen[$key] = true;
    //     }

    //     // 2. Check for duplicates already existing in the database (bulk check)
    //     $existing = NominatedEmployee::where(function ($query) use ($employees) {
    //         foreach ($employees as $entry) {
    //             $query->orWhere(function ($q) use ($entry) {
    //                 $q->where('event_schedule_id', $entry['event_schedule_id'])
    //                     ->where('control_no', $entry['control_no']);
    //             });
    //         }
    //     })->get(['event_schedule_id', 'control_no']);

    //     if ($existing->isNotEmpty()) {
    //         $first = $existing->first();
    //         throw new \Exception(
    //             "Employee with control_no {$first->control_no} is already nominated for this schedule.",
    //             422
    //         );
    //     }

    //     // 3. Insert inside a transaction
    //     return DB::transaction(function () use ($employees, $user) {
    //         $controlNos = array_column($employees, 'control_no');

    //         $employeeData = vwEmployee::whereIn('ControlNo', $controlNos)
    //             ->get()
    //             ->keyBy('ControlNo');

    //         $nominees = [];
    //         foreach ($employees as $entry) {
    //             $data = $employeeData->get($entry['control_no']);

    //             if (!$data) {
    //                 throw new \Exception("Employee with control_no {$entry['control_no']} not found.", 404);
    //             }

    //             $nominees[] = NominatedEmployee::create([
    //                 'event_id'          => $entry['event_id'],
    //                 'control_no'        => $entry['control_no'],
    //                 'designation'       => $data->position ?? null,
    //                 'status'            => $data->status ?? null,
    //                 'full_name'         => $data->name ?? null,
    //                 'sg'                => $data->sg ?? null,
    //                 'level'             => $data->level ?? null,
    //                 'office'            => $user->office ?? null,
    //                 'nominate_reason'   => $entry['nominate_reason'] ?? null,
    //                 'event_schedule_id' => $entry['event_schedule_id'],
    //                 'nominate_status'   => 'Pending', // default pending
    //             ]);
    //         }
    //         $this->notifyHrAdmins($nominees, $user);
    //         return $nominees;
    //     });
    // }

    protected function notifyHrAdmins(array $nominees, Authenticatable $user): void
    {
        try {
            $hrAdmins = User::role('hr_admin')->get();

            if ($hrAdmins->isEmpty()) {
                return;
            }

            // Isang notification kada schedule, hindi kada empleyado
            collect($nominees)
                ->groupBy('event_schedule_id')
                ->each(function ($group, $scheduleId) use ($hrAdmins, $user) {
                    Notification::send($hrAdmins, new EmployeesNominatedNotification(
                        eventId: (int) $group->first()->event_id,
                        scheduleId: (int) $scheduleId,
                        office: $user->office ?? null,
                        count: $group->count(),
                        nominatedBy: $user->name ?? null,
                    ));
                });
        } catch (\Throwable $e) {
            // Hindi dapat pumalya ang nomination kapag pumalya ang notification
            Log::warning('Failed to notify hr_admin: ' . $e->getMessage());
        }
    }

    //pending 
    public function update(int $nominatedId, array $validated, Authenticatable $user)
    {
        $nominee = NominatedEmployee::findOrFail($nominatedId);

        // Check kung may ibang record (maliban dito) na may parehong event_id + control_no
        $exists = NominatedEmployee::where('event_id', $validated['event_id'])
            ->where('control_no', $validated['control_no'])
            ->where('id', '!=', $nominatedId)
            ->exists();

        if ($exists) {
            throw new \Exception(
                "Employee with control_no {$validated['control_no']} is already nominated for this event.",
                422
            );
        }

        return DB::transaction(function () use ($nominee, $validated) {
            $nominee->update([
                'event_id'   => $validated['event_id'],
                'control_no' => $validated['control_no'],
            ]);

            return $nominee;
        });
    }

    public function remove(int $nominatedEmployeeId)
    {
        $employee = NominatedEmployee::find($nominatedEmployeeId);

        if (!$employee) {
            throw new \Exception('Nominated employee not found.');
        }

        $employee->delete();

        return $employee;
    }

    // for nomination
 public function employeeListForNomination(
    string $trainingName,
    Authenticatable $user,
    ?int $sgFrom = null,
    ?int $sgTo = null
) {
    // sg_from lang = iisang SG; sg_to lang = iisang SG din
    if ($sgFrom !== null && $sgTo === null) {
        $sgTo = $sgFrom;
    } elseif ($sgFrom === null && $sgTo !== null) {
        $sgFrom = $sgTo;
    }

    // kung baliktad ang pagkakalagay, ayusin
    if ($sgFrom !== null && $sgTo !== null && $sgFrom > $sgTo) {
        [$sgFrom, $sgTo] = [$sgTo, $sgFrom];
    }

    return vwEmployee::with(['xTraining' => function ($query) use ($trainingName) {
            $query->select('ControlNo', 'training', 'Dates', 'NumHours', 'Conductor', 'DateFrom', 'DateTo', 'Type')
                ->where('training', $trainingName);
        }])
        ->select('ControlNo', 'name', 'office', 'position', 'status', 'sg')
        ->where('office', $user->office)
        ->when(
            $sgFrom !== null && $sgTo !== null,
            fn ($q) => $q->whereBetween('sg', [$sgFrom, $sgTo])
        )
        ->get()
        ->map(function ($emp) {
            $emp->trainingCount = $emp->xTraining->count();
            $emp->isAlreadyTrained = $emp->trainingCount > 0;
            return $emp;
        });
}
    public function editReason(array $validated, int $nominatedEmployeeId)
    {

        $employee = NominatedEmployee::find($nominatedEmployeeId);


        if (!$employee) {
            throw new \Exception('Employee not found', 404);
        }

        $employee->update([
            'nominate_reason' => $validated['nominate_reason'],
        ]);

        return $employee;
    }


    public function getEmployeePhoto(string $controlNo): ?array
    {
        $path = xPersonal::where('ControlNo', $controlNo)->value('Pics');

        if (!$path || !is_file($path)) {
            return null;
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            return null;
        }

        return [
            'contents' => $contents,
            'mime'     => mime_content_type($path) ?: 'image/jpeg',
        ];
    }
}
