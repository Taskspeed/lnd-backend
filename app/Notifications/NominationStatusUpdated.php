<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NominationStatusUpdated extends Notification
{
    use Queueable;
  public function __construct(protected $nominatedEmployee) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $status = $this->nominatedEmployee->nominate_status;

        return [
            'type' => 'nomination_status_updated',
            'title' => "Nomination {$this->nominatedEmployee->nominate_status}",
            'message' => "{$this->nominatedEmployee->full_name}'s nomination has been {$this->nominatedEmployee->nominate_status}.",
            'event_id' => $this->nominatedEmployee->event_id,
            'event_schedule_id' => $this->nominatedEmployee->event_schedule_id,
            'nominated_employee_id' => $this->nominatedEmployee->id,
        ];
    }
}
