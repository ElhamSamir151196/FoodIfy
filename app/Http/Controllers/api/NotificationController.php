<?php

namespace App\Http\Controllers\Api;

use App\Actions\Notification\GetNotificationsAction;
use App\Actions\Notification\GetUnreadCountAction;
use App\Actions\Notification\MarkAllNotificationsAsReadAction;
use App\Actions\Notification\MarkNotificationAsReadAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;

    public function index(Request $request, GetNotificationsAction $action): JsonResponse
    {
        $notifications = $action->execute($request->user());

        return $this->success(data: [
            'notifications' => NotificationResource::collection($notifications),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'total'        => $notifications->total(),
            ],
        ]);
    }

    public function markAsRead(string $id, Request $request, MarkNotificationAsReadAction $action): JsonResponse
    {
        $marked = $action->execute($request->user(), $id);

        if (!$marked) {
            return $this->error('Notification not found.', 404);
        }

        return $this->success(message: 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request, MarkAllNotificationsAsReadAction $action): JsonResponse
    {
        $action->execute($request->user());

        return $this->success(message: 'All notifications marked as read.');
    }

    public function unreadCount(Request $request, GetUnreadCountAction $action): JsonResponse
    {
        $count = $action->execute($request->user());

        return $this->success(data: ['unread_count' => $count]);
    }
}