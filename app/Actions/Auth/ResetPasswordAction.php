<?php

namespace App\Actions\Auth;

use App\Repositories\UserRepository;
use App\Services\OtpService;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{
    public function __construct(
        private readonly OtpService     $otp,
        private readonly UserRepository $users,
    ) {}

    public function execute(string $phone, string $password): bool
    {
        if (!$this->otp->isResetVerified($phone)) {
            return false;
        }

        $this->users->updatePassword($phone, Hash::make($password));
        $this->otp->clearResetVerified($phone);

        return true;
    }
}