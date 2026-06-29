<?php

namespace App\Actions\Auth;

use App\Services\OtpService;

class ForgetPasswordAction
{
    public function __construct(private readonly OtpService $otp) {}

    public function execute(string $phone): void
    {
        $this->otp->send($phone, 'reset');
    }
}