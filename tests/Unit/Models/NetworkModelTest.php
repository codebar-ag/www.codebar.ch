<?php

use App\Enums\LocaleEnum;
use App\Enums\NetworkCategoryEnum;
use App\Enums\NetworkStatusEnum;
use App\Models\Network;
use App\Models\NetworkUser;

it('returns its users ordered by sort', function () {
    $network = Network::factory()->create(['key' => 'docuware', 'locale' => LocaleEnum::DE->value]);

    $second = NetworkUser::factory()->create(['network_key' => 'docuware', 'name' => 'Second', 'sort' => 20]);
    $first = NetworkUser::factory()->create(['network_key' => 'docuware', 'name' => 'First', 'sort' => 10]);
    NetworkUser::factory()->create(['network_key' => 'other', 'name' => 'Other']);

    expect($network->users->pluck('name')->all())->toBe(['First', 'Second']);
})->group('network', 'network-model');

it('shares users between locale rows of the same company', function () {
    $de = Network::factory()->create(['key' => 'docuware', 'locale' => LocaleEnum::DE->value]);
    $en = Network::factory()->create(['key' => 'docuware', 'locale' => LocaleEnum::EN->value]);

    NetworkUser::factory()->create(['network_key' => 'docuware']);

    expect($de->users)->toHaveCount(1)
        ->and($en->users)->toHaveCount(1);
})->group('network', 'network-model');

it('filters by published and active scopes', function () {
    Network::factory()->create(['key' => 'a', 'published' => true, 'status' => NetworkStatusEnum::ACTIVE->value]);
    Network::factory()->create(['key' => 'b', 'published' => false, 'status' => NetworkStatusEnum::ACTIVE->value]);
    Network::factory()->create(['key' => 'c', 'published' => true, 'status' => NetworkStatusEnum::ENDED->value]);

    expect(Network::published()->pluck('key')->all())->toBe(['a', 'c'])
        ->and(Network::published()->active()->pluck('key')->all())->toBe(['a']);
})->group('network', 'network-model');

it('casts locale, category, status and published', function () {
    $network = Network::factory()->create([
        'locale' => LocaleEnum::DE->value,
        'category' => NetworkCategoryEnum::SPONSORING->value,
        'status' => NetworkStatusEnum::ACTIVE->value,
        'published' => 1,
    ]);

    expect($network->locale)->toBe(LocaleEnum::DE)
        ->and($network->category)->toBe(NetworkCategoryEnum::SPONSORING)
        ->and($network->status)->toBe(NetworkStatusEnum::ACTIVE)
        ->and($network->published)->toBeTrue();
})->group('network', 'network-model');

it('derives the website host without www', function () {
    expect(Network::factory()->make(['website' => 'https://www.iway.ch'])->websiteHost())->toBe('iway.ch')
        ->and(Network::factory()->make(['website' => 'https://start.docuware.com'])->websiteHost())->toBe('start.docuware.com')
        ->and(Network::factory()->make(['website' => null])->websiteHost())->toBeNull();
})->group('network', 'network-model');
