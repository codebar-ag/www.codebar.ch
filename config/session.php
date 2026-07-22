<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Session overrides
    |--------------------------------------------------------------------------
    |
    | Every option not listed here falls back to Laravel's internal default
    | config (vendor/laravel/framework/config/session.php).
    |
    */

    // The __Host- prefix requires Secure, Path=/ and no Domain attribute,
    // so it is only applied when SESSION_SECURE_COOKIE is enabled.
    'cookie' => env(
        'SESSION_COOKIE',
        (env('SESSION_SECURE_COOKIE') ? '__Host-' : '')
            .Str::slug((string) env('APP_NAME', 'laravel'), '_').'_session'
    ),

];
