<?php

declare(strict_types=1);

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Cache overrides
    |--------------------------------------------------------------------------
    |
    | Every option not listed here falls back to Laravel's internal default
    | config (vendor/laravel/framework/config/cache.php). "prefix" is kept
    | here to preserve the current key format (Laravel's default changed
    | its separator style).
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Merged into the framework's default stores. Only the stores this
    | application adds or changes are listed here.
    |
    */

    'stores' => [

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => env('DB_CACHE_TABLE', 'cache'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],

        // Holds the schedule heartbeat only. Its own table, because Cache::flush() and
        // optimize:clear empty the whole table of the default store.
        'health' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => 'health_cache',
        ],

    ],

    'prefix' => env('CACHE_PREFIX', Str::slug((string) env('APP_NAME', 'laravel'), '_').'_cache_'),

];
