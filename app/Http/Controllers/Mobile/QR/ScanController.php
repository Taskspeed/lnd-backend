<?php

namespace App\Http\Controllers\Mobile\QR;

use App\Http\Controllers\Controller;
use App\Models\Employee\EmployeeAttendance;
use App\Models\Employee\NominatedEmployee;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    //



    // store employee attendance
public function store(Request $request)
{
    $validated = $request->validate([
        'nominated_employee_id' => 'required|exists:nominated_employees,id',
        'scan_date'             => 'required|date',
        'morning_in'            => 'nullable|date_format:H:i:s',
        'morning_out'           => 'nullable|date_format:H:i:s',
        'afternoon_in'          => 'nullable|date_format:H:i:s',
        'afternoon_out'         => 'nullable|date_format:H:i:s',
    ]);

    $nominatedEmployee = NominatedEmployee::find($validated['nominated_employee_id']);

    $attendance = EmployeeAttendance::updateOrCreate(
        [
            // search keys — ito ang gagamitin para tignan kung existing na ang record
            'nominated_employee_id' => $validated['nominated_employee_id'],
            'scan_date'             => $validated['scan_date'],
        ],
        [
            // values na i-uupdate/isesave
            'morning_in'    => $validated['morning_in'] ?? null,
            'morning_out'   => $validated['morning_out'] ?? null,
            'afternoon_in'  => $validated['afternoon_in'] ?? null,
            'afternoon_out' => $validated['afternoon_out'] ?? null,
            'control_no'    => $nominatedEmployee->control_no,
            'name'          => $nominatedEmployee->full_name,
        ]
    );

    return response()->json($attendance, 201);
}
}
