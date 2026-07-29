<?php

declare(strict_types=1);

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

    'name' => env('APP_NAME', 'codebar Solutions AG'),

    /*
     * Pinned explicitly rather than left to the framework default: robots.txt,
     * the sitemap, canonical tags, OG URLs and the JSON-LD @id anchors are all
     * built from this. A wrong value here poisons every absolute URL we emit.
     */
    'url' => env('APP_URL', 'https://www.codebar.ch'),

    'timezone' => env('APP_TIMEZONE', 'Europe/Zurich'),

    'locale' => env('APP_LOCALE', 'de_CH'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'de_CH'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'de_CH'),

];
