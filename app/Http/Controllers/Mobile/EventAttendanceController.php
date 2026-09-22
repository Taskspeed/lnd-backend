<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\EventEmployeeAttendanceResource;
use App\Models\Event\EventSchedule;
use App\Models\RSP\yOffice;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;


class EventAttendanceController extends Controller
{
    //

    use ApiResponseTrait;

    public function getListEvent()
    {


        $event  = EventSchedule::with(['event', 'scheduleDateTime'])->get();

        return  $this->successMessage($event, 'event list', 200);
    }
    
    public function getEventScheduleEmployeeAttendance(int $eventScheduleId)
    {
        $event = EventSchedule::with([
            'event',
            'scheduleDateTime',
            'nominatedEmployee' => function ($query) {
                $query->with(['employeeAttendances'])
                    ->select('event_id', 'event_schedule_id', 'id', 'control_no', 'full_name', 'office', 'designation', 'status');
            },
        ])->find($eventScheduleId);

        if ($event && $event->nominatedEmployee) {
            $event->nominatedEmployee->each(function ($employee) {
                $office = YOffice::where('Descriptions', $employee->office)->first();
                $employee->office_abbr = $office->Abbr ?? null;
            });
        }

        return $this->successMessage(new EventEmployeeAttendanceResource($event), 'event list', 200);
    }
}
