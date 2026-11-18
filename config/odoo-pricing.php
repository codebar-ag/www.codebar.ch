<?php

declare(strict_types=1);

return [
    'checked_at' => '2026-11-18',

    'sources' => [
        'license' => 'https://www.odoo.com/de_DE/pricing',
        'hosting' => 'https://www.odoo.sh/pricing',
    ],

    'eur_to_chf' => 1.0,

    'license' => [
        'full' => ['yearly' => 4490, 'monthly' => 5690],
        'light' => ['yearly' => 790, 'monthly' => 790],
    ],

    'hosting' => [
        'worker' => 6000,
        'storage_gb' => 20,
        'staging' => 1500,
        'factor' => ['yearly' => 0.8, 'monthly' => 1.0],
    ],

    'tiers' => [
        ['up_to' => 25, 'workers' => 1],
        ['up_to' => 50, 'workers' => 2],
        ['up_to' => 75, 'workers' => 3],
        ['up_to' => 100, 'workers' => 4],
        ['up_to' => 125, 'workers' => 5],
        ['up_to' => 150, 'workers' => 6],
    ],

    'storage_gb_per_user' => 5,
    'storage_gb_max' => 512,

    'max_users' => 150,
    'full_users' => ['min' => 1, 'default' => 10],
    'light_users' => ['min' => 0, 'default' => 0],
    'staging' => 1,
];
