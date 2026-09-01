<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */
    // Only allow CORS on specific API paths to reduce attack surface
    // 'paths' => ['api*'],
    // 'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
    // 'allowed_origins' => [env('FRONT_END_URL', 'http://localhost:3002'), 'http://localhost:3000','http://localhost:3001','http://localhost:3002', 'http://127.0.0.1:3000','https://cpcb.vercel.app'],
    // 'allowed_origins_patterns' => [],
    // 'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept', 'X-XSRF-TOKEN'],
    // 'exposed_headers' => [],
    // 'max_age' => 600,
    // 'supports_credentials' => false,

    'paths' => ['api*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
    'allowed_origins' => [env('FRONT_END_URL', 'http://localhost:3002'), 'http://localhost:3000','http://localhost:3001','http://localhost:3002', 'http://127.0.0.1:3000',],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 600,
    'supports_credentials' => false,
];
