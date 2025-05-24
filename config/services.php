<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'upayments' => [
        'merchant_id' => env('UPAYMENTS_MERCHANT_ID'),
        'username' => env('UPAYMENTS_USERNAME'),
        'password' => env('UPAYMENTS_PASSWORD'),
        'api_key' => env('UPAYMENTS_API_KEY'),
        'test_mode' => env('UPAYMENTS_TEST_MODE', 1),

        'iban' => env('UPAYMENTS_IBAN'),
    ],

    'myfatoorah' => [
        'username' => env('MYFATOORAH_USERNAME'),
        'password' => env('MYFATOORAH_PASSWORD'),
        'test_mode' => env('MYFATOORAH_TEST_MODE', 1),

        'api_key' => env('MYFATOORAH_API_KEY'),
    ],

    'google_maps' => [
        'key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'websockets' => [
        'base_url' => env('WEBSOCKETS_BASE_URL'),
        'username' => env('WEBSOCKETS_USERNAME'),
        'password' => env('WEBSOCKETS_PASSWORD'),
    ],
];
