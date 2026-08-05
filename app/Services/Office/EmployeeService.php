<?php

namespace App\Services\Office;

use App\Models\Employee\NominatedEmployee;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function create(array $validated, Authenticatable $user)
    {
        $employees = $validated['employee'];

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
            $nominees = [];
            foreach ($employees as $entry) {
                $nominees[] = NominatedEmployee::create([
                    'event_id'   => $entry['event_id'],
                    'control_no' => $entry['control_no'],
                    'office'     => $user->office,
                ]);
            }
            return $nominees;
        });
    }

    // public function update(int $nominatedId, array $validated, Authenticatable $user)
    // {
    //     $nominee = NominatedEmployee::findOrFail($nominatedId);

    //     // Check kung may ibang record (maliban dito) na may parehong event_id + control_no
    //     $exists = NominatedEmployee::where('event_id', $validated['event_id'])
    //         ->where('control_no', $validated['control_no'])
    //         ->where('id', '!=', $nominatedId)
    //         ->exists();

    //     if ($exists) {
    //         throw new \Exception(
    //             "Employee with control_no {$validated['control_no']} is already nominated for this event.",
    //             422
    //         );
    //     }

    //     return DB::transaction(function () use ($nominee, $validated) {
    //         $nominee->update([
    //             'event_id'   => $validated['event_id'],
    //             'control_no' => $validated['control_no'],
    //         ]);

    //         return $nominee;
    //     });
    // }

    public function delete(int $nominatedId)
    {

        $employee = NominatedEmployee::find($nominatedId);

        if (!$employee) {
            throw new \Exception('nominate employee not found');
        }

        $employee->delete();

        return $employee;
    }

}
