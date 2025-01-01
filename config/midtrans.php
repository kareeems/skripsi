<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', 'your-server-key-here'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'your-client-key-here'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
