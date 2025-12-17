<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\GeneralNotification;


class NotificationController extends Controller
{

    public function index(Request $request)
    {
        return response()->json([
            'notifications' => $request->user()->notifications
        ]);
    }



    public function markAsRead($id, Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read successfully'
        ]);
    }


}
