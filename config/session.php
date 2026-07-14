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

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

];
