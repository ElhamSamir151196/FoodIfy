<?php

namespace App\Actions\Notification;

use App\Models\User;
use App\Repositories\NotificationRepository;

class GetUnreadCountAction
{
    public function __construct(private readonly NotificationRepository $notifications) {}

    public function execute(User $user): int
    {
        return $this->notifications->unreadCount($user);
    }
}