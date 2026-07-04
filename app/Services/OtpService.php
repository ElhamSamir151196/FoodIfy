<?php

namespace App\Services;

use App\Contracts\SmsGatewayInterface;
use Illuminate\Support\Facades\Cache;

class OtpService
{
    public function __construct(private readonly SmsGatewayInterface $smsGateway) {}

    // ── Keys ──────────────────────────────
    public function registerKey(string $phone): string
    {
        return "otp:register:{$phone}";
    }

    public function resetKey(string $phone): string
    {
        return "otp:reset:{$phone}";
    }

    public function verifiedKey(string $phone): string
    {
        return "otp:reset_verified:{$phone}";
    }

    // ── Send ──────────────────────────────
    public function send(string $phone, string $type = 'register'): void
    {
        $otp = (string) rand(1000, 9999);
        $key = $type === 'register'
            ? $this->registerKey($phone)
            : $this->resetKey($phone);

        Cache::put($key, $otp, now()->addMinute());

        $this->smsGateway->send(
            $phone,
            "Your Foodify OTP is: {$otp}. Valid for 1 minute."
        );
    }

    // ── Verify ────────────────────────────
    public function verify(string $phone, string $otp, string $type = 'register'): bool
    {
        $key    = $type === 'register'
            ? $this->registerKey($phone)
            : $this->resetKey($phone);
        $stored = Cache::get($key);

        if (!$stored || $stored !== $otp) {
            return false;
        }

        Cache::forget($key);

        if ($type === 'reset') {
            Cache::put($this->verifiedKey($phone), true, now()->addMinutes(5));
        }

        return true;
    }

    // ── Reset Verified ────────────────────
    public function isResetVerified(string $phone): bool
    {
        return (bool) Cache::get($this->verifiedKey($phone));
    }

    public function clearResetVerified(string $phone): void
    {
        Cache::forget($this->verifiedKey($phone));
    }
}