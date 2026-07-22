<?php

use App\Enums\LocaleEnum;
use App\Enums\NetworkCategoryEnum;
use App\Models\Network;
use App\Models\NetworkUser;
use Database\Seeders\NetworksTableSeeder;
use Database\Seeders\NetworkUsersTableSeeder;

it('seeds every company in both locales', function () {
    $this->seed(NetworksTableSeeder::class);

    $expectedKeys = [
        'wieland-business-solutions',
        'pst',
        'docuware',
        'odoo',
        'iway',
        'baselhack',
        'laravel-meetups',
        'swiss-made-software',
        'swiss-digital-services',
    ];

    foreach ($expectedKeys as $key) {
        foreach (LocaleEnum::cases() as $locale) {
            expect(Network::where('key', $key)->where('locale', $locale->value)->exists())->toBeTrue();
        }
    }

    expect(Network::count())->toBe(count($expectedKeys) * count(LocaleEnum::cases()));
})->group('network', 'seeders');

it('seeds the tiers, categories and the baselhack subpage', function () {
    $this->seed(NetworksTableSeeder::class);

    $docuware = Network::where('key', 'docuware')->where('locale', LocaleEnum::DE->value)->first();
    $odoo = Network::where('key', 'odoo')->where('locale', LocaleEnum::DE->value)->first();
    $baselhack = Network::where('key', 'baselhack')->where('locale', LocaleEnum::DE->value)->first();

    expect($docuware->tier_label)->toBe('Silver Partner')
        ->and($docuware->category)->toBe(NetworkCategoryEnum::SOFTWARE)
        ->and($odoo->tier_label)->toBe('Learning Partner')
        ->and($baselhack->tier_label)->toBe('Silver Sponsor')
        ->and($baselhack->page_slug)->toBe('baselhack');
})->group('network', 'seeders');

it('seeds the contact persons unpublished until they consent', function () {
    $this->seed(NetworkUsersTableSeeder::class);

    expect(NetworkUser::count())->toBe(5)
        ->and(NetworkUser::where('published', true)->count())->toBe(0)
        ->and(NetworkUser::where('network_key', 'docuware')->first()->name)->toBe('Vincenzo Carbone');
})->group('network', 'seeders');

it('is idempotent', function () {
    $this->seed(NetworksTableSeeder::class);
    $this->seed(NetworksTableSeeder::class);
    $this->seed(NetworkUsersTableSeeder::class);
    $this->seed(NetworkUsersTableSeeder::class);

    expect(Network::count())->toBe(18)
        ->and(NetworkUser::count())->toBe(5);
})->group('network', 'seeders');
