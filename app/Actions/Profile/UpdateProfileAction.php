<?php

namespace App\Actions\Profile;

use App\Models\User;
use App\Repositories\UserRepository;

class UpdateProfileAction
{
    public function __construct(private readonly UserRepository $users) {}

    public function execute(User $user, array $data): User
    {
        // Map incoming 'full_name' to the underlying 'name' column
        if (isset($data['full_name'])) {
            $data['name'] = $data['full_name'];
            unset($data['full_name']);
        }

        return $this->users->updateProfile($user, $data);
    }
}