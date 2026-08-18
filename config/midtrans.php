<?php

return [
    'enabled' => env('MIDTRANS_ENABLED', false),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'payment_type' => env('MIDTRANS_PAYMENT_TYPE', 'snap'),
    'sanitize' => true,
    'enable_3ds' => true,
    // List of enabled payment channels shown on Midtrans Snap page.
    // Can be overridden by setting MIDTRANS_ENABLED_PAYMENTS in .env as a comma-separated list.
    'enabled_payments' => env('MIDTRANS_ENABLED_PAYMENTS') ? explode(',', env('MIDTRANS_ENABLED_PAYMENTS')) : [
        'gopay', 'dana', 'shopeepay', 'qris', 'credit_card', 'bank_transfer'
    ],
];
