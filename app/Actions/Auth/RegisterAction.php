<?php

namespace App\Actions\Auth;

use App\Services\OtpService;
use Illuminate\Support\Facades\Cache;

class RegisterAction
{
    public function __construct(private readonly OtpService $otp) {}

    public function execute(array $data): void
    {
        Cache::put(
            "register:pending:{$data['phone']}",
            $data,
            now()->addMinutes(10)
        );

        $this->otp->send($data['phone'], 'register');
    }
}