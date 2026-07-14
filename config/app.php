<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application overrides
    |--------------------------------------------------------------------------
    |
    | Every option not listed here falls back to Laravel's internal default
    | config (vendor/laravel/framework/config/app.php), which is merged in
    | automatically. Only real deviations from that default live here.
    |
    */

    'name' => env('APP_NAME', 'paperflakes AG'),

    'timezone' => env('APP_TIMEZONE', 'Europe/Zurich'),

    'locale' => env('APP_LOCALE', 'de_CH'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'de_CH'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'de_CH'),

];
