<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class FcmChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (! $notifiable->fcm_token) {
            return;
        }

        if (! method_exists($notification, 'toFcm')) {
            return;
        }

        $data = $notification->toFcm($notifiable);

        Http::withToken(config('services.fcm.server_key'))
            ->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $notifiable->fcm_token,
                'notification' => [
                    'title' => $data['title'],
                    'body'  => $data['body'],
                ],
                'data' => $data['data'] ?? [],
            ]);
    }
}
