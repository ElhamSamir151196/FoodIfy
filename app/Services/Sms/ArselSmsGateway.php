<?php

namespace App\Services\Sms;

use App\Contracts\SmsGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ArselSmsGateway implements SmsGatewayInterface
{
    public function send(string $phone, string $message): void
    {
        $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.arsel.key'),
            ])
            ->post(config('services.arsel.endpoint'), [
                'from'    => config('services.arsel.sender'), // ≤ 11 حرف
                'to'      => [$phone],                        // لازم يبقى array
                'content' => $message,                        // مش message
            ]);

        // ── مؤقت: نسجل كل حاجة عشان نتابع الرد ──
        Log::info('Arsel SMS response', [
            'status'  => $response->status(),
            'body'    => $response->body(),
            'sent_to' => $phone,
        ]);

        if ($response->failed()) {
            Log::error('Arsel SMS failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        }
    }
}