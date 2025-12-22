<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Services\PushNotificationService;
use App\Notifications\Channels\FcmChannel;


class SystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public array $channels = [],
        public array $data = []
    ) {}

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
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->title)
            ->line($this->message);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    /**
     * Custom FCM channel
     */
    public function toFcm($notifiable)
    {
        if (! $notifiable->fcm_token) {
            return;
        }

        app(PushNotificationService::class)
            ->sendToToken(
                $notifiable->fcm_token,
                $this->title,
                $this->message,
                $this->data
            );
    }
}
