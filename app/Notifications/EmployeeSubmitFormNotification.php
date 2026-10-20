<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmployeeSubmitFormNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected $event,
        protected $schedule,
        protected string $formName,
        protected ?string $employeeName,
        protected ?string $controlNo,
        protected int $submissionId,
        protected ?int $nominatedEmployeeId = null,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $who = $this->employeeName ?? $this->controlNo ?? 'An employee';

        return [
            'type'                  => 'form_submitted',
            'title'                 => 'Form Submitted',
            'message'               => "{$who} submitted the {$this->formName} for \"{$this->event->title_name}\".",
            'event_id'              => $this->event->id,
            'event_schedule_id'     => $this->schedule->id,
            'submission_id'         => $this->submissionId,
            'control_no'            => $this->controlNo,
            'nominated_employee_id' => $this->nominatedEmployeeId,
        ];
    }
}
