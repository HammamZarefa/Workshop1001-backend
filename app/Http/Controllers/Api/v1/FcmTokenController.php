<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use App\Services\PushNotificationService;
class FcmTokenController extends ApiController
{
    public function __construct(
        protected PushNotificationService $pushService
    ) {}

    public function store(Request $request)
    {
        return $this->tryCall(function () use ($request) {

            $request->validate([
                'fcm_token' => 'required|string',
            ]);

            $user = auth()->user();

            $user->update([
                'fcm_token' => $request->fcm_token,
            ]);

            return [
                'message' => 'FCM token saved successfully',
            ];
        }, 'Failed to save FCM token');
    }
}
