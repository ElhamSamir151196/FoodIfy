<?php

namespace App\Actions\Auth;

use App\Services\OtpService;

class VerifyResetOtpAction
{
    public function __construct(private readonly OtpService $otp) {}

    public function execute(string $phone, string $otp): bool
    {
        return $this->otp->verify($phone, $otp, 'reset');
    }
}