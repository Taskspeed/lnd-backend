<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeesNominatedNotification extends Notification
{
  use Queueable;

    public function __construct(
        public int $eventId,
        public int $scheduleId,
        public ?string $office,
        public int $count,
        public ?string $nominatedBy = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $office = $this->office ?? 'An office';

        return [
            'type'        => 'employees_nominated', // ginagamit ng frontend para sa route
            'title'       => 'New Employee Nomination',
            'message'     => "{$office} nominated {$this->count} employee(s) and is waiting for your review.",
            'event_id'    => $this->eventId,
            'schedule_id' => $this->scheduleId,
            'office'      => $this->office,
            'nominated_by' => $this->nominatedBy,
        ];
    }
}
