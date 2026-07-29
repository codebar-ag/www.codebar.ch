<?php

declare(strict_types=1);

use App\Enums\NetworkCategoryEnum;
use App\Enums\NetworkStatusEnum;
use App\Models\Network;
use App\Models\NetworkUser;

it('returns its users ordered by sort', function () {
    $network = Network::factory()->create(['key' => 'docuware']);

    $second = NetworkUser::factory()->create(['network_key' => 'docuware', 'name' => 'Second', 'sort' => 20]);
    $first = NetworkUser::factory()->create(['network_key' => 'docuware', 'name' => 'First', 'sort' => 10]);
    NetworkUser::factory()->create(['network_key' => 'other', 'name' => 'Other']);

    expect($network->users->pluck('name')->all())->toBe(['First', 'Second']);
})->group('network', 'network-model');

it('filters by published and active scopes', function () {
    Network::factory()->create(['key' => 'a', 'published' => true, 'status' => NetworkStatusEnum::ACTIVE->value]);
    Network::factory()->create(['key' => 'b', 'published' => false, 'status' => NetworkStatusEnum::ACTIVE->value]);
    Network::factory()->create(['key' => 'c', 'published' => true, 'status' => NetworkStatusEnum::ENDED->value]);

    expect(Network::published()->pluck('key')->all())->toBe(['a', 'c'])
        ->and(Network::published()->active()->pluck('key')->all())->toBe(['a']);
})->group('network', 'network-model');

it('casts category, status and published', function () {
    $network = Network::factory()->create([
        'category' => NetworkCategoryEnum::SPONSORING->value,
        'status' => NetworkStatusEnum::ACTIVE->value,
        'published' => 1,
    ]);

    expect($network->category)->toBe(NetworkCategoryEnum::SPONSORING)
        ->and($network->status)->toBe(NetworkStatusEnum::ACTIVE)
        ->and($network->published)->toBeTrue();
})->group('network', 'network-model');

it('translates name, excerpt and tier_label per locale', function () {
    $network = Network::factory()->create([
        'name' => ['de_CH' => 'DocuWare DE', 'en_CH' => 'DocuWare EN'],
        'excerpt' => ['de_CH' => 'Deutscher Teaser', 'en_CH' => 'English teaser'],
        'tier_label' => ['de_CH' => 'Silber', 'en_CH' => 'Silver'],
    ]);

    expect($network->getTranslation('name', 'de_CH'))->toBe('DocuWare DE')
        ->and($network->getTranslation('name', 'en_CH'))->toBe('DocuWare EN')
        ->and($network->getTranslation('excerpt', 'de_CH'))->toBe('Deutscher Teaser')
        ->and($network->getTranslation('excerpt', 'en_CH'))->toBe('English teaser')
        ->and($network->getTranslation('tier_label', 'de_CH'))->toBe('Silber')
        ->and($network->getTranslation('tier_label', 'en_CH'))->toBe('Silver');
})->group('network', 'network-model');

it('derives the website host without www', function () {
    expect(Network::factory()->make(['website' => 'https://www.iway.ch'])->websiteHost())->toBe('iway.ch')
        ->and(Network::factory()->make(['website' => 'https://start.docuware.com'])->websiteHost())->toBe('start.docuware.com')
        ->and(Network::factory()->make(['website' => null])->websiteHost())->toBeNull();
})->group('network', 'network-model');
