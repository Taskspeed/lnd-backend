<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventCreated extends Notification
{
    use Queueable;
  public function __construct(protected $event, protected $schedule) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
        'type' => 'event_created',
        'title' => 'New Training Event',
        'message' => "A new event \"{$this->event->title_name}\" has been created for your office.",
        'event_id' => $this->event->id,
        'event_schedule_id' => $this->schedule->id,
    ];
    }
}
