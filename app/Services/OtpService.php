<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class OtpService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            new Basic(
                config('services.vonage.key'),
                config('services.vonage.secret')
            )
        );
    }

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

        $this->client->sms()->send(
            new SMS(
                $phone,
                config('services.vonage.sms_from'),
                "Your Foodify OTP is: {$otp}. Valid for 1 minute."
            )
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