<?php

namespace App\Services\Office;

use App\Models\Employee\NominatedEmployee;
use App\Models\Event\Event;
use App\Models\Event\EventSchedule;
use App\Models\RSP\vwEmployee;
use App\Models\RSP\xPersonal;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function create(?array $validated, Authenticatable $user)
    {
        $employees = $validated['employee'];

        // 1. Check for duplicates within the submitted request itself
        $seen = [];
        foreach ($employees as $entry) {
            $key = $entry['event_schedule_id'] . '-' . $entry['control_no'];
            if (isset($seen[$key])) {
                throw new \Exception(
                    "Duplicate entry in request: control_no {$entry['control_no']} for event_schedule_id {$entry['event_schedule_id']} was submitted more than once.",
                    422
                );
            }
            $seen[$key] = true;
        }

        // 2. Check for duplicates already existing in the database (bulk check)
        $existing = NominatedEmployee::where(function ($query) use ($employees) {
            foreach ($employees as $entry) {
                $query->orWhere(function ($q) use ($entry) {
                    $q->where('event_schedule_id', $entry['event_schedule_id'])
                        ->where('control_no', $entry['control_no']);
                });
            }
        })->get(['event_schedule_id', 'control_no']);

        if ($existing->isNotEmpty()) {
            $first = $existing->first();
            throw new \Exception(
                "Employee with control_no {$first->control_no} is already nominated for this schedule.",
                422
            );
        }

        // 3. Insert inside a transaction
        return DB::transaction(function () use ($employees, $user) {
            $controlNos = array_column($employees, 'control_no');

            $employeeData = vwEmployee::whereIn('ControlNo', $controlNos)
                ->get()
                ->keyBy('ControlNo');

            $nominees = [];
            foreach ($employees as $entry) {
                $data = $employeeData->get($entry['control_no']);

                if (!$data) {
                    throw new \Exception("Employee with control_no {$entry['control_no']} not found.", 404);
                }

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
                    'nominate_status'   => 'Pending', // default pending
                ]);
            }

            return $nominees;
        });
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
    public function employeeListForNomination(string $trainingName, Authenticatable $user)
    {

        $employee = vwEmployee::with(['xTraining' => function ($query) use ($trainingName) {
            $query->select('ControlNo', 'training', 'Dates', 'NumHours', 'Conductor', 'DateFrom', 'DateTo', 'Type')
                ->where('training', $trainingName);
        }])
            ->select('ControlNo', 'name', 'office', 'position', 'status')
            ->where('office', $user->office)
            ->get()
            ->map(function ($emp) {
                $emp->trainingCount = $emp->xTraining->count();
                $emp->isAlreadyTrained = $emp->trainingCount > 0;
                return $emp;
            });


        return $employee;
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

    //   public function getEmployeePhoto(string $controlNo)
    // {

    //     $employee = xPersonal::where('ControlNo', $controlNo)->select('Pics')->first();

    //     if (!$employee || !$employee->Pics) {
    //         return response()->json(['error' => 'Image not found'], 404);
    //     }

    //     // Convert Windows UNC path to accessible path
    //     // \\192.168.2.205\Payroll Database\... → //192.168.2.205/Payroll Database/...
    //     $path = str_replace('\\', '/', $employee->Pics);
    //     $path = ltrim($path, '/');
    //     // Result: 192.168.2.205/Payroll Database/IDPICTURE/.../filename.jpg

    //     // Full UNC for file_get_contents (Linux uses smb:// or mapped path)
    //     // If Laravel server is Windows and has access to the share:
    //     $windowsPath = $employee->Pics; // use raw UNC path directly

    //     if (!file_exists($windowsPath)) {
    //         return response()->json(['error' => 'Image not found'], 404);
    //     }

    //     $fileContents = file_get_contents($windowsPath);
    //     $mimeType = mime_content_type($windowsPath) ?: 'image/jpeg';

    //     return response($fileContents, 200)
    //         ->header('Content-Type', $mimeType)
    //         ->header('Cache-Control', 'public, max-age=3600');
    // }

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
