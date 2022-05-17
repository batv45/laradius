<?php
return [
    'service' => env('PAYMENT_SERVICE'),
    'iyzico' => [
        'api_key' => env('PAYMENT_IYZICO_API_KEY'),
        'api_secret' => env('PAYMENT_IYZICO_API_SECRET'),
        'base_url' => env('PAYMENT_IYZICO_BASE_URL')
    ]
];
