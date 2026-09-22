<?php

namespace App\Http\Resources\Mobile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventEmployeeAttendanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'event_id' => $this->event_id,
            'venue_name' => $this->venue_name,
            'mode_name' => $this->mode_name,
            'hours' => $this->hours,
            'qualifications' => $this->qualifications,
            'fee' => $this->fee,
            'source_name' => $this->source_name,
            'type_name' => $this->type_name,
            'category_name' => $this->category_name,
            'conducted_by' => $this->conducted_by,
            'scheduleId' => $this->id, // adjust kung ibang column
            'computedStatus' => $this->computed_status, // palitan kung accessor/method

            'event' => $this->whenLoaded('event', function () {
                return [
                    'title_name' => $this->event->title_name,
                    'learning_intervention' => $this->event->learning_intervention,
'created_at' => $this->event->created_at ? Carbon::parse($this->event->created_at)->format('F d, Y') : null,
                    'event_id' => $this->event->event_id,
                ];
            }),

            'schedule_date_time' => $this->whenLoaded('scheduleDateTime', function () {
                return $this->scheduleDateTime->map(function ($sched) {
                    return [
                        'id' => $sched->id,
                        'event_schedule_id' => $sched->event_schedule_id,
'schedule_date' => $sched->schedule_date ? Carbon::parse($sched->schedule_date)->format('F d, Y') : null,
                        'morning_in' => $sched->morning_in,
                        'morning_out' => $sched->morning_out,
                        'afternoon_in' => $sched->afternoon_in,
                        'afternoon_out' => $sched->afternoon_out,
                    ];
                });
            }),

            'nominated_employee' => $this->whenLoaded('nominatedEmployee', function () {
                return $this->nominatedEmployee->map(function ($employee) {
                    return [
                        'event_id' => $employee->event_id,
                        'event_schedule_id' => $employee->event_schedule_id,
                        'full_name' => $employee->full_name,
                        'office' => $employee->office,
                        'designation' => $employee->designation,
                        'status' => $employee->status,
                        'office_abbr' => $employee->office_abbr,
                        'nominated_employee_id' => $employee->id,
                        'employee_attendances' => $employee->employeeAttendances->map(function ($att) {
                            return [
                                'id' => $att->id,
                                'nominated_employee_id' => $att->nominated_employee_id,
                                'control_no' => $att->control_no,
                                'name' => $att->name,
'scan_date' => $att->scan_date ? Carbon::parse($att->scan_date)->format('F d, Y') : null,
                                'morning_in' => $att->morning_in,
                                'morning_out' => $att->morning_out,
                                'afternoon_in' => $att->afternoon_in,
                                'afternoon_out' => $att->afternoon_out,
                            ];
                        }),
                    ];
                });
            }),
        ];
    }
}
