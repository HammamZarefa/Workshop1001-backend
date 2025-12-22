<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends ApiController
{
  public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate($this->perPage($request));

        return $this->paginatedResponse($notifications);
    }

    public function markAsRead(string $id, Request $request): JsonResponse
    {
        /** @var DatabaseNotification|null $notification */
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if (! $notification) {
            return $this->error('Notification not found', 404);
        }

        $notification->markAsRead();

        return $this->success(null, 'Notification marked as read');
    }

 
    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()
            ->unreadNotifications
            ->markAsRead();

        return $this->success(null, 'All notifications marked as read');
    }

}
