<?php
return [
    'name' => env('APP_NAME', 'BRUCE FIRE'), 'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false), 'url' => env('APP_URL', 'http://127.0.0.1:8000'),
    'timezone' => 'America/Lima', 'locale' => 'es', 'fallback_locale' => 'en',
    'key' => env('APP_KEY'), 'cipher' => 'AES-256-CBC',
];
