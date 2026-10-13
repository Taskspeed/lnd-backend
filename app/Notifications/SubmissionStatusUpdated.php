<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class SubmissionStatusUpdated extends Notification
{
    public function __construct(
        public int $submissionId,
        public string $formName,
        public string $status,  
        public string $controlNo,        // 'Approved' | 'Returned'
        public ?int $eventId = null,
        public ?int $eventScheduleId = null,
        public ?string $remarks = null,
      
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $approved = $this->status === 'Approved';

        return [
            'type'          => 'submission_status',
            'submission_id' => $this->submissionId,
            'event_id'      => $this->eventId,
            'event_schedule_id'      => $this->eventScheduleId,
            'form_name'     => $this->formName,
            'controlNo'     => $this->controlNo,
            'status'        => $this->status,
            'title'         => $approved
                ? "{$this->formName} approved"
                : "{$this->formName} returned",
            'message'       => $approved
                ? "Your {$this->formName} submission has been approved."
                : "Your {$this->formName} submission was returned. Please review the remarks and resubmit.",
            'remarks'       => $this->remarks,
        ];
    }
}