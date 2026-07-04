<?php

namespace App\Actions\Admin;

use App\Models\User;
use App\Repositories\UserRepository;

class GetUserDetailsAction
{
    public function __construct(private readonly UserRepository $users) {}

    public function execute(int $id): ?User
    {
        return $this->users->findWithOrders($id);
    }
}