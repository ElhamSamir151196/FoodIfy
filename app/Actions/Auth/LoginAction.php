<?php

namespace App\Actions\Auth;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class LoginAction
{
    public function __construct(private readonly UserRepository $users) {}

    public function execute(string $phone, string $password): array
    {
        $user = $this->users->findByPhone($phone);

        if (!$user || !Hash::check($password, $user->password)) {
            return ['success' => false, 'reason' => 'invalid_credentials'];
        }

        if (!$user->is_active) {
            return ['success' => false, 'reason' => 'inactive'];
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ['success' => true, 'user' => $user, 'token' => $token];
    }
}