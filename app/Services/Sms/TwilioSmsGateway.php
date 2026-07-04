<?php

namespace App\Services\Sms;

use App\Contracts\SmsGatewayInterface;
use Twilio\Rest\Client;

class TwilioSmsGateway implements SmsGatewayInterface
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function send(string $phone, string $message): void
    {
        $this->client->messages->create($phone, [
            'from' => config('services.twilio.from'),
            'body' => $message,
        ]);
    }
}