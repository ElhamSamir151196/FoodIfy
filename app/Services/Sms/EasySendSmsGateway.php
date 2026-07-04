<?php

namespace App\Services\Sms;

use App\Contracts\SmsGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EasySendSmsGateway implements SmsGatewayInterface
{
    public function send(string $phone, string $message): void
    {
        try {
            $response = Http::withHeaders([
                'apikey' => config('services.easysendsms.key'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post(config('services.easysendsms.base_url') . '/v1/rest/sms/send', [
                'from' => config('services.easysendsms.from'),
                'to' => $this->formatEgyptianNumber($phone),
                'text' => $message,
                'type' => '0',
            ]);

            $result = $response->json();

            // EasySendSMS بيرجع 200 حتى لو فيه error
            if (!isset($result['message_id'])) {
                throw new \RuntimeException(
                    'EasySendSMS failed: ' . json_encode($result)
                );
            }

            Log::info('EasySendSMS sent', [
                'phone' => $phone,
                'message_id' => $result['message_id'],
            ]);

        } catch (\Throwable $e) {
            Log::error('EasySendSMS Exception', [
                'phone' => $phone,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Format Egyptian number to international format
     */
    private function formatEgyptianNumber(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '20' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '20')) {
            $phone = '20' . $phone;
        }

        return $phone;
    }
}