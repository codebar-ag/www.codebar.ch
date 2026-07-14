<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hashing overrides
    |--------------------------------------------------------------------------
    |
    | Every option not listed here falls back to Laravel's internal default
    | config (vendor/laravel/framework/config/hashing.php). The "bcrypt" key
    | must be kept whole (this merge isn't deep) since it overrides "limit".
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
        'verify' => env('HASH_VERIFY', true),
        'limit' => env('BCRYPT_LIMIT', 72),
    ],

];
