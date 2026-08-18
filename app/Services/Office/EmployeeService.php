<?php

namespace App\Services\Office;

use App\Models\Employee\NominatedEmployee;
use App\Models\Event\Event;
use App\Models\Event\EventSchedule;
use App\Models\RSP\vwEmployee;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function create(array $validated, Authenticatable $user)
    {
        $employees = $validated['employee'];

        // $eventIds = array_unique(array_column($employees, 'event_id'));

        // $invalidEvents = Event::whereIn('id', $eventIds)
        //     ->whereIn('status', ['Cancel', 'Complete'])
        //     ->pluck('id');

        // if ($invalidEvents->isNotEmpty()) {
        //     throw new \Exception('Cannot nominate employee because the event is already cancelled or completed.');
        // }

        // 1. Check for duplicates within the submitted request itself
        $seen = [];
        foreach ($employees as $entry) {
            $key = $entry['event_id'] . '-' . $entry['control_no'];
            if (isset($seen[$key])) {
                throw new \Exception(
                    "Duplicate entry in request: control_no {$entry['control_no']} for event_id {$entry['event_id']} was submitted more than once.",
                    422
                );
            }
            $seen[$key] = true;
        }

        // 2. Check for duplicates already existing in the database (bulk check)
        $existing = NominatedEmployee::where(function ($query) use ($employees) {
            foreach ($employees as $entry) {
                $query->orWhere(function ($q) use ($entry) {
                    $q->where('event_id', $entry['event_id'])
                        ->where('control_no', $entry['control_no']);
                });
            }
        })->get(['event_id', 'control_no']);

        if ($existing->isNotEmpty()) {
            $first = $existing->first();
            throw new \Exception(
                "Employee with control_no {$first->control_no} is already nominated for this event.",
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
                    'event_id'    => $entry['event_id'],
                    'control_no'  => $entry['control_no'],
                    'designation' => $data->position ?? null,
                    'status'      => $data->status ?? null,
                    'full_name'   => $data->name ?? null,
                    'sg'          => $data->sg ?? null,
                    'level'       => $data->level ?? null,
                    'office'      => $user->office ?? null,
                    'event_schedule_id'      => $entry['event_schedule_id'],
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

    public function delete(int $nominatedEmployeeId)
    {
        $employee = NominatedEmployee::find($nominatedEmployeeId);

        if (!$employee) {
            throw new \Exception('Nominated employee not found.');
        }

        $event = Event::select('id', 'status')->where('id', $employee->event_id)->first();

        if (!$event) {
            throw new \Exception('Event not found.');
        }

        if (in_array($event->status, ['Cancel', 'Complete'])) {
            throw new \Exception('You cannot delete nominated employee because the event is already cancelled or completed.');
        }

        $employee->delete();

        return $employee;
    }
}
