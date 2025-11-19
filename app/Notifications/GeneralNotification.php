<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $data = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Get the notification channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Data to store in database notifications table.
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}
