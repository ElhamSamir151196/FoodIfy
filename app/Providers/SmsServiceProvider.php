<?php

namespace App\Providers;

use App\Contracts\SmsGatewayInterface;
use App\Services\Sms\ArselSmsGateway;
use App\Services\Sms\TwilioSmsGateway;
use App\Services\Sms\VonageSmsGateway;
use Illuminate\Support\ServiceProvider;
use App\Services\Sms\EasySendSmsGateway;
use App\Services\Sms\SmsMisrGateway;

class SmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsGatewayInterface::class, function () {
            return match (config('services.sms.driver', 'vonage')) {
                'smsmisr' => new SmsMisrGateway(),
                'easysendsms' => new EasySendSmsGateway(),
                'twilio' => new TwilioSmsGateway(),
                'arsel'  => new ArselSmsGateway(),
                default  => new VonageSmsGateway(),
            };
        });
    }
}