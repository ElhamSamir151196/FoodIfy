<?php

namespace App\Contracts;

interface SmsGatewayInterface
{
    /**
     * Send an SMS message to the given phone number.
     */
    public function send(string $phone, string $message): void;
}