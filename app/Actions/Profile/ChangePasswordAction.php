<?php

namespace App\Actions\Profile;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class ChangePasswordAction
{
    public function __construct(private readonly UserRepository $users) {}

    public function execute(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $this->users->updatePasswordFor($user, Hash::make($newPassword));

        return true;
    }
}