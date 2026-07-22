<?php

use App\Enums\LocaleEnum;
use App\Enums\NetworkStatusEnum;
use App\Enums\SessionKeyEnum;
use App\Models\Network;
use App\Models\NetworkUser;
use Illuminate\Support\Str;

use function Pest\Laravel\get;
use function Pest\Laravel\withSession;

it('renders the baselhack subpage for both locales', function (string $locale) {
    foreach (LocaleEnum::cases() as $rowLocale) {
        Network::factory()->create([
            'key' => 'baselhack',
            'locale' => $rowLocale->value,
            'name' => 'BaselHack',
            'tier_label' => 'Silver Sponsor',
            'website' => 'https://www.baselhack.ch',
            'page_slug' => 'baselhack',
        ]);
    }

    withSession([SessionKeyEnum::LANGUAGE->value => $locale])
        ->get(route(Str::slug($locale).'.network.show', ['slug' => 'baselhack']))
        ->assertOk()
        ->assertSee('BaselHack')
        ->assertSee('Silver Sponsor')
        ->assertSee('baselhack.ch');
})->with([LocaleEnum::DE->value, LocaleEnum::EN->value])->group('network');

it('shows published contact persons on the subpage', function () {
    Network::factory()->create([
        'key' => 'baselhack',
        'locale' => LocaleEnum::DE->value,
        'name' => 'BaselHack',
        'page_slug' => 'baselhack',
    ]);

    NetworkUser::factory()->create([
        'network_key' => 'baselhack',
        'name' => 'Publica Person',
        'published' => true,
    ]);

    NetworkUser::factory()->create([
        'network_key' => 'baselhack',
        'name' => 'Privata Person',
        'published' => false,
    ]);

    get(route('de-ch.network.show', ['slug' => 'baselhack']))
        ->assertOk()
        ->assertSee('Publica Person')
        ->assertDontSee('Privata Person');
})->group('network');

it('returns 404 for an unknown slug', function () {
    get(route('de-ch.network.show', ['slug' => 'unknown']))
        ->assertNotFound();
})->group('network');

it('returns 404 when the network is unpublished', function () {
    Network::factory()->create([
        'key' => 'baselhack',
        'locale' => LocaleEnum::DE->value,
        'name' => 'BaselHack',
        'page_slug' => 'baselhack',
        'published' => false,
    ]);

    get(route('de-ch.network.show', ['slug' => 'baselhack']))
        ->assertNotFound();
})->group('network');

it('returns 404 when the network has ended, even when accessed directly', function () {
    Network::factory()->create([
        'key' => 'baselhack',
        'locale' => LocaleEnum::DE->value,
        'name' => 'BaselHack',
        'page_slug' => 'baselhack',
        'published' => true,
        'status' => NetworkStatusEnum::ENDED->value,
    ]);

    get(route('de-ch.network.show', ['slug' => 'baselhack']))
        ->assertNotFound();
})->group('network');

it('returns 404 when no blade view exists for the page slug', function () {
    Network::factory()->create([
        'key' => 'no-view',
        'locale' => LocaleEnum::DE->value,
        'name' => 'No View AG',
        'page_slug' => 'no-view',
    ]);

    get(route('de-ch.network.show', ['slug' => 'no-view']))
        ->assertNotFound();
})->group('network');
