<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationRepository
{
    public function getByUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $user->notifications()->paginate($perPage);
    }

    public function findById(User $user, string $id): ?DatabaseNotification
    {
        return $user->notifications()->whereKey($id)->first();
    }

    public function markAsRead(DatabaseNotification $notification): void
    {
        $notification->markAsRead();
    }

    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }

    public function unreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }
}