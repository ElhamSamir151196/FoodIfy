<?php

namespace App\Services\Sms;

use App\Contracts\SmsGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsMisrGateway implements SmsGatewayInterface
{
    public function send(string $phone, string $message): void
    {
        try {
            $formattedPhone = $this->formatEgyptianNumber($phone);
            $environment = config('services.smsmisr.environment', '2');

            // ✅ سجل المحتوى قبل الإرسال
            Log::info('📱 SMS Content Preview', [
                'to' => $phone,
                'formatted_phone' => $formattedPhone,
                'message' => $message,
                'environment' => $environment === '1' ? 'Live' : 'Test',
                'sender' => config('services.smsmisr.sender'),
            ]);

            $response = Http::asForm()->post(
                config('services.smsmisr.base_url') . '/SMS/',
                [
                    'environment' => $environment,
                    'username'    => config('services.smsmisr.username'),
                    'password'    => config('services.smsmisr.password'),
                    'language'    => '1',
                    'sender'      => config('services.smsmisr.sender'),
                    'mobile'      => $formattedPhone,
                    'message'     => $message,
                ]
            );

            $result = $response->body();
            $json = json_decode($result, true);

            if (isset($json['code']) && $json['code'] != '1901') {
                throw new \RuntimeException(
                    'SMS Misr failed. Response: ' . $result
                );
            }

            // ✅ سجل الـ Response
            Log::info('✅ SMS Misr API Response', [
                'phone' => $phone,
                'api_response' => $json,
                'message_preview' => $message,
            ]);

        } catch (\Throwable $e) {
            Log::error('❌ SMS Misr Exception', [
                'phone' => $phone,
                'message_content' => $message,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

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