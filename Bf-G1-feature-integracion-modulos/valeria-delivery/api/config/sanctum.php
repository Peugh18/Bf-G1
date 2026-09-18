<?php
return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', '127.0.0.1:5173,127.0.0.1:5174,localhost:5173,localhost:5174,127.0.0.1:8000')),
    'guard' => ['web'], 'expiration' => 120,
    'middleware' => [
        'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
        'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
        'validate_csrf_token' => Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ],
];
