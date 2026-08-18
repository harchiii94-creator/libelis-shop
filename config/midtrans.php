<?php

return [
    'enabled' => env('MIDTRANS_ENABLED', false),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'payment_type' => env('MIDTRANS_PAYMENT_TYPE', 'snap'),
    'sanitize' => true,
    'enable_3ds' => true,
];
