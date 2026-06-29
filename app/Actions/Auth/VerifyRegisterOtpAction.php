<?php

namespace App\Actions\Auth;

use App\Repositories\UserRepository;
use App\Services\OtpService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class VerifyRegisterOtpAction
{
    public function __construct(
        private readonly OtpService      $otp,
        private readonly UserRepository  $users,
    ) {}

    public function execute(string $phone, string $otp): array
    {
        if (!$this->otp->verify($phone, $otp, 'register')) {
            return ['verified' => false, 'reason' => 'invalid_otp'];
        }

        $data = Cache::pull("register:pending:{$phone}");

        if (!$data) {
            return ['verified' => false, 'reason' => 'session_expired'];
        }

        $user  = $this->users->create([
            ...$data,
            'password' => Hash::make($data['password']),
            'phone_verified_at' => now(), // ✅

        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ['verified' => true, 'user' => $user, 'token' => $token];
    }
}