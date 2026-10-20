<?php

namespace App\Traits;

use App\Models\Employee\NominatedEmployee;
use App\Models\RSP\vwEmployee;
use App\Models\User;
use App\Notifications\EmployeeSubmitFormNotification;
use Illuminate\Support\Facades\Notification;



trait FormSubmittedNotification
{
   public function notifyHrAdmins($event, $schedule, $form_submit,$formName): void
    {
        try {
            $controlNo = $form_submit->control_no ?? null;

            $employeeName = $controlNo
                ? vwEmployee::where('ControlNo', $controlNo)->value('name')
                : null;

            $nominatedId =  NominatedEmployee::where('control_no', $controlNo)
                ->where('event_schedule_id', $schedule->id)
                ->value('id');


            $admins = User::role('hr_admin')->get(); // Spatie

            if ($admins->isEmpty()) {
                return;
            }

            Notification::send($admins, new EmployeeSubmitFormNotification(
                $event,
                $schedule,
                $formName,
                $employeeName,
                $controlNo,
                $form_submit->id,
                $nominatedId,
            ));
        } catch (\Throwable $e) {
            report($e); // hindi dapat pumalya ang submission dahil lang sa notification
        }
    }
}