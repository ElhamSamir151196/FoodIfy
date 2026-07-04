<?php

namespace App\Services\Sms;

use App\Contracts\SmsGatewayInterface;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;
use Illuminate\Support\Facades\Log;

class VonageSmsGateway implements SmsGatewayInterface
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



    public function send(string $phone, string $message): void
    {
        try {
            $response = $this->client->sms()->send(
                new SMS(
                    $phone,
                    config('services.vonage.sms_from'),
                    $message
                )
            );

            $sms = $response->current();

            Log::info('Vonage SMS', [
                'status' => $sms->getStatus(),
                'message_id' => $sms->getMessageId(),
            ]);

        } catch (\Throwable $e) {

            Log::error('Vonage Exception', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}