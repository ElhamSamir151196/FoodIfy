<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByPhone(string $phone): ?User
    {
        return User::where('phone', $phone)->first();
    }

    public function updatePassword(string $phone, string $hashedPassword): void
    {
        User::where('phone', $phone)
            ->update(['password' => $hashedPassword]);
    }
}