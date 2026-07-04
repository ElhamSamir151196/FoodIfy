<?php

namespace App\Actions\Notification;

use App\Models\User;
use App\Repositories\NotificationRepository;

class MarkNotificationAsReadAction
{
    public function __construct(private readonly NotificationRepository $notifications) {}

    public function execute(User $user, string $notificationId): bool
    {
        $notification = $this->notifications->findById($user, $notificationId);

        if (!$notification) {
            return false;
        }

        $this->notifications->markAsRead($notification);

        return true;
    }
}