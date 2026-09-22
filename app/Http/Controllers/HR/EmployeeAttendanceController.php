<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee\EmployeeAttendance;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EventScheduleDateTime;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeAttendanceController extends Controller
{

        use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
 public function show(int $nominatedEmployeeId)
{
    $employee = NominatedEmployee::findOrFail($nominatedEmployeeId);
 
    $schedules = EventScheduleDateTime::where('event_schedule_id', $employee->event_schedule_id)->get();
 
    $attendances = EmployeeAttendance::where('nominated_employee_id', $employee->id)->get();
 
    $attendanceSummary = $schedules->map(function ($schedule) use ($attendances) {
        $scheduleDate = Carbon::parse($schedule->schedule_date)->format('Y-m-d');
 
        // Hanapin ang attendance record na tumutugma sa petsa ng schedule
        $attendance = $attendances->first(function ($att) use ($scheduleDate) {
            return Carbon::parse($att->scan_date)->format('Y-m-d') === $scheduleDate;
        });
 
        $isLateMorning = null;
        $isLateAfternoon = null;
        $lateMinutesMorning = 0;
        $lateMinutesAfternoon = 0;
 
        if ($attendance) {
            $scheduledMorningIn = Carbon::parse($schedule->morning_in);
            $actualMorningIn = Carbon::parse($attendance->morning_in);
            $isLateMorning = $actualMorningIn->gt($scheduledMorningIn);
            $lateMinutesMorning = $isLateMorning
                ? $scheduledMorningIn->diffInMinutes($actualMorningIn)
                : 0;
 
            $scheduledAfternoonIn = Carbon::parse($schedule->afternoon_in);
            $actualAfternoonIn = Carbon::parse($attendance->afternoon_in);
            $isLateAfternoon = $actualAfternoonIn->gt($scheduledAfternoonIn);
            $lateMinutesAfternoon = $isLateAfternoon
                ? $scheduledAfternoonIn->diffInMinutes($actualAfternoonIn)
                : 0;
        }
 
        return [
          'schedule_date' => Carbon::parse($scheduleDate)->format('F d, Y'),
            'status'                 => $attendance ? 'present' : 'absent',
            'scheduled'              => [
                'morning_in'    => $schedule->morning_in,
                'morning_out'   => $schedule->morning_out,
                'afternoon_in'  => $schedule->afternoon_in,
                'afternoon_out' => $schedule->afternoon_out,
            ],
            'actual'                 => $attendance ? [
                'morning_in'    => $attendance->morning_in,
                'morning_out'   => $attendance->morning_out,
                'afternoon_in'  => $attendance->afternoon_in,
                'afternoon_out' => $attendance->afternoon_out,
            ] : null,
            'is_late_morning'        => $isLateMorning,
            'late_minutes_morning'   => $lateMinutesMorning,
            'is_late_afternoon'      => $isLateAfternoon,
            'late_minutes_afternoon' => $lateMinutesAfternoon,
        ];
    });
 
    return $this->successMessage(
        ['attendance_summary' => $attendanceSummary],
        'success',
        200
    );
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
