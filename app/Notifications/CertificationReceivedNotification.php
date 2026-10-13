<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CertificationReceivedNotification extends Notification
{
    public function __construct(
        public int $certificateId,
        public string $controlNo,
        public string $title,
  
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'              => 'certificate', // key ito sa notificationRoutes ng frontend
            'certificate_id'    => $this->certificateId,
            'controlNo'         => $this->controlNo,
            'title'             => 'Certificate available',
            'message'           => "Your certificate for \"{$this->title}\" is now available.",
        ];
    }
}