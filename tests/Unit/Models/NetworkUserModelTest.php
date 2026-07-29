<?php

declare(strict_types=1);

use App\Models\Network;
use App\Models\NetworkUser;

it('returns the company via the network relation, translated per locale', function () {
    Network::factory()->create([
        'key' => 'docuware',
        'name' => ['de_CH' => 'DocuWare DE', 'en_CH' => 'DocuWare EN'],
    ]);

    $networkUser = NetworkUser::factory()->create(['network_key' => 'docuware']);
    $network = $networkUser->network()->firstOrFail();

    expect($network->getTranslation('name', 'de_CH'))->toBe('DocuWare DE')
        ->and($network->getTranslation('name', 'en_CH'))->toBe('DocuWare EN');
})->group('network', 'network-user-model');

it('filters by the published scope', function () {
    NetworkUser::factory()->create(['name' => 'Visible', 'published' => true]);
    NetworkUser::factory()->create(['name' => 'Hidden', 'published' => false]);

    expect(NetworkUser::published()->pluck('name')->all())->toBe(['Visible']);
})->group('network', 'network-user-model');

it('displays the avatar url first, then gravatar, then the placeholder', function () {
    $withAvatar = NetworkUser::factory()->make([
        'avatar_url' => 'https://res.cloudinary.com/demo/image/upload/avatar.jpg',
        'email' => 'dario@example.com',
    ]);
    $withEmail = NetworkUser::factory()->make(['avatar_url' => null, 'email' => 'dario@example.com']);
    $withNothing = NetworkUser::factory()->make(['avatar_url' => null, 'email' => null]);

    expect($withAvatar->avatarDisplayUrl(64))->toContain('res.cloudinary.com')->toContain('w_64')
        ->and($withEmail->avatarDisplayUrl(64))->toContain('gravatar.com')
        ->and($withNothing->avatarDisplayUrl(64))->toBe('/images/placeholders/avatar-sample.svg');
})->group('network', 'network-user-model');

it('displays the cover url or the placeholder, never gravatar', function () {
    $withCover = Network::factory()->make(['cover_url' => 'https://res.cloudinary.com/demo/image/upload/cover.jpg']);
    $withoutCover = Network::factory()->make(['cover_url' => null]);

    expect($withCover->coverDisplayUrl())->toBe('https://res.cloudinary.com/demo/image/upload/cover.jpg')
        ->and($withoutCover->coverDisplayUrl())->toBe('/images/placeholders/network-company.svg');
})->group('network', 'network-user-model');
