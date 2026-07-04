<?php

namespace App\Actions\Admin;

use App\Models\User;
use App\Repositories\UserRepository;

class ToggleUserStatusAction
{
    public function __construct(private readonly UserRepository $users) {}

    public function execute(User $user): User
    {
        return $this->users->toggleStatus($user);
    }
}