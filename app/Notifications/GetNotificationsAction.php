<?php

namespace App\Actions\Notification;

use App\Models\User;
use App\Repositories\NotificationRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class GetNotificationsAction
{
    public function __construct(private readonly NotificationRepository $notifications) {}

    public function execute(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->notifications->getByUser($user, $perPage);
    }
}