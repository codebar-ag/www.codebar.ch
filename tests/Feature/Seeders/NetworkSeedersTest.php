<?php

use App\Enums\LocaleEnum;
use App\Enums\NetworkCategoryEnum;
use App\Models\Network;
use App\Models\NetworkUser;
use Database\Seeders\NetworksTableSeeder;
use Database\Seeders\NetworkUsersTableSeeder;

use function Pest\Laravel\seed;

it('seeds every company in both locales', function () {
    seed(NetworksTableSeeder::class);

    $expectedKeys = [
        'wieland-business-solutions',
        'pst',
        'docuware',
        'odoo',
        'iway',
        'baselhack',
        'swiss-laravel-association',
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

it('seeds the tiers and categories', function () {
    seed(NetworksTableSeeder::class);

    $docuware = Network::where('key', 'docuware')->where('locale', LocaleEnum::DE->value)->firstOrFail();
    $odoo = Network::where('key', 'odoo')->where('locale', LocaleEnum::DE->value)->firstOrFail();
    $baselhack = Network::where('key', 'baselhack')->where('locale', LocaleEnum::DE->value)->firstOrFail();

    expect($docuware->tier_label)->toBeNull()
        ->and($docuware->category)->toBe(NetworkCategoryEnum::SOFTWARE)
        ->and($odoo->tier_label)->toBeNull()
        ->and($baselhack->tier_label)->toBe('Silver Sponsor')
        ->and($baselhack->page_slug)->toBeNull();
})->group('network', 'seeders');

it('seeds unpublished contact persons with real channels where known', function () {
    seed(NetworkUsersTableSeeder::class);

    $vincenzo = NetworkUser::where('network_key', 'docuware')->firstOrFail();
    $dario = NetworkUser::where('network_key', 'wieland-business-solutions')->firstOrFail();
    $sarah = NetworkUser::where('network_key', 'pst')->firstOrFail();
    $domenik = NetworkUser::where('network_key', 'odoo')->firstOrFail();
    $patrick = NetworkUser::where('network_key', 'iway')->firstOrFail();
    $baselhack = NetworkUser::where('network_key', 'baselhack')->firstOrFail();

    expect(NetworkUser::count())->toBe(6)
        ->and(NetworkUser::where('published', true)->count())->toBe(0)
        ->and($vincenzo->name)->toBe('Vincenzo Carbone')
        ->and($vincenzo->role)->toBe('DocuWare Schweiz')
        ->and($vincenzo->email)->toBe('vincenzo.carbone@docuware.com')
        ->and($vincenzo->avatar_url)->toBe('/images/placeholders/avatar-sample.svg')
        ->and($dario->email)->toBe('dario.wieland@business-solutions.gmbh')
        ->and($sarah->email)->toBe('sarah.faessler@pstgmbh.ch')
        ->and($domenik->name)->toBe('Domenik Friedrich')
        ->and($domenik->email)->toBe('domf@odoo.com')
        ->and($patrick->linkedin)->not->toBeNull()
        ->and($patrick->phone)->not->toBeNull()
        ->and($baselhack->name)->toBe('BaselHack')
        ->and($baselhack->email)->toBe('info@baselhack.ch');
})->group('network', 'seeders');

it('is idempotent', function () {
    seed(NetworksTableSeeder::class);
    seed(NetworksTableSeeder::class);
    seed(NetworkUsersTableSeeder::class);
    seed(NetworkUsersTableSeeder::class);

    expect(Network::count())->toBe(18)
        ->and(NetworkUser::count())->toBe(6);
})->group('network', 'seeders');
