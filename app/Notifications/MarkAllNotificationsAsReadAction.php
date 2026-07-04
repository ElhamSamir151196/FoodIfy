<?php

namespace App\Actions\Notification;

use App\Models\User;
use App\Repositories\NotificationRepository;

class MarkAllNotificationsAsReadAction
{
    public function __construct(private readonly NotificationRepository $notifications) {}

    public function execute(User $user): void
    {
        $this->notifications->markAllAsRead($user);
    }
}