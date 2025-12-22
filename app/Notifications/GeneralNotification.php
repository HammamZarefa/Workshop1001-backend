<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\FcmChannel;

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
    $pref = $notifiable->notificationPreference;

    if (! $pref) {
        return ['database', FcmChannel::class];
    }

    $channels = [];

    if ($pref->database) {
        $channels[] = 'database';
    }

    if ($pref->fcm) {
        $channels[] = FcmChannel::class;
    }

    if ($pref->mail) {
        $channels[] = 'mail';
    }

    return $channels;
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


    public function toFcm($notifiable)
    {
    return [
        'title' => $this->title,
        'body'  => $this->message,
        'data'  => $this->data,
    ];
}
}
