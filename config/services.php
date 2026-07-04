<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses'      => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack'    => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'sms'      => [
        'driver' => env('SMS_DRIVER', 'vonage'), // vonage | twilio | arsel
    ],
    'vonage'   => [
        'key'      => env('VONAGE_KEY'),
        'secret'   => env('VONAGE_SECRET'),
        'sms_from' => env('VONAGE_FROM', 'Foodify'),
    ],
    'easysendsms' => [
        'key'       => env('EASYSENDSMS_KEY'),
        'base_url'  => env('EASYSENDSMS_BASE_URL', 'https://restapi.easysendsms.app'),
        'from'      => env('EASYSENDSMS_FROM', 'Foodify'),
    ],
    'smsmisr' => [
        'username'  => env('SMSMISR_USERNAME'),
        'password'  => env('SMSMISR_PASSWORD'),
        'sender'    => env('SMSMISR_SENDER', 'Foodify'),
        'base_url'  => env('SMSMISR_BASE_URL', 'https://smsmisr.com/api/webapi/'),
    ],
    'arsel'    => [
        'endpoint' => env('ARSEL_ENDPOINT'),
        'key'      => env('ARSEL_KEY'),
        'sender'   => env('ARSEL_SENDER'),
    ],
    'twilio'   => [
        'sid'   => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from'  => env('TWILIO_FROM'),
    ],

    'paymob'   => [
        'api_key'        => env('PAYMOB_API_KEY'),
        'integration_id' => env('PAYMOB_INTEGRATION_ID'),
        'iframe_id'      => env('PAYMOB_IFRAME_ID'),
        'hmac_secret'    => env('PAYMOB_HMAC_SECRET'),
    ],

];
