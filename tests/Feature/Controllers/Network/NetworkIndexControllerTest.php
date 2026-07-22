<?php

use App\Enums\LocaleEnum;
use App\Enums\NetworkCategoryEnum;
use App\Enums\NetworkStatusEnum;
use App\Enums\SessionKeyEnum;
use App\Models\Network;
use App\Models\NetworkUser;
use Illuminate\Support\Str;

use function Pest\Laravel\get;
use function Pest\Laravel\withSession;

it('returns 200 for both locales', function (string $locale) {
    withSession([SessionKeyEnum::LANGUAGE->value => $locale])
        ->get(route(Str::slug($locale).'.network.index'))
        ->assertOk();
})->with([LocaleEnum::DE->value, LocaleEnum::EN->value])->group('network');

it('shows a published network with tier label, excerpt and a website icon link', function () {
    Network::factory()->create([
        'key' => 'docuware',
        'locale' => LocaleEnum::DE->value,
        'name' => 'DocuWare',
        'category' => NetworkCategoryEnum::SOFTWARE->value,
        'tier_label' => 'Silver Partner',
        'excerpt' => 'DMS/ECM',
        'website' => 'https://start.docuware.com',
    ]);

    get(route('de-ch.network.index'))
        ->assertOk()
        ->assertSee('DocuWare')
        ->assertSee('Silver Partner')
        ->assertSee('DMS/ECM')
        ->assertSee('href="https://start.docuware.com"', escape: false)
        ->assertSee('title="start.docuware.com"', escape: false)
        ->assertSee(NetworkCategoryEnum::SOFTWARE->getLabel());
})->group('network');

it('shows the drawing illustration when no logo is set but a drawing exists', function () {
    Network::factory()->create([
        'key' => 'docuware',
        'locale' => LocaleEnum::DE->value,
        'name' => 'DocuWare',
        'cover_url' => null,
    ]);

    get(route('de-ch.network.index'))
        ->assertOk()
        ->assertSee('images/network/docuware.svg', escape: false)
        ->assertDontSee('images/placeholders/network-company.svg', escape: false);
})->group('network');

it('shows the placeholder illustration instead of the name when no logo is set', function () {
    Network::factory()->create([
        'locale' => LocaleEnum::DE->value,
        'name' => 'Placeholder AG',
        'cover_url' => null,
    ]);

    get(route('de-ch.network.index'))
        ->assertOk()
        ->assertSee('images/placeholders/network-company.svg', escape: false);
})->group('network');

it('shows the logo image instead of the placeholder when a logo is set', function () {
    Network::factory()->create([
        'locale' => LocaleEnum::DE->value,
        'name' => 'Logo AG',
        'cover_url' => 'https://res.cloudinary.com/codebar/image/upload/logo-ag.webp',
    ]);

    get(route('de-ch.network.index'))
        ->assertOk()
        ->assertSee('logo-ag.webp', escape: false)
        ->assertDontSee('images/placeholders/network-company.svg', escape: false);
})->group('network');

it('does not show unpublished networks', function () {
    Network::factory()->create([
        'locale' => LocaleEnum::DE->value,
        'name' => 'Hidden Company AG',
        'published' => false,
    ]);

    get(route('de-ch.network.index'))
        ->assertOk()
        ->assertDontSee('Hidden Company AG');
})->group('network');

it('does not show ended networks', function () {
    Network::factory()->create([
        'locale' => LocaleEnum::DE->value,
        'name' => 'Former Partner AG',
        'status' => NetworkStatusEnum::ENDED->value,
    ]);

    get(route('de-ch.network.index'))
        ->assertOk()
        ->assertDontSee('Former Partner AG');
})->group('network');

it('does not show networks of another locale', function () {
    Network::factory()->create([
        'locale' => LocaleEnum::EN->value,
        'name' => 'English Only AG',
    ]);

    get(route('de-ch.network.index'))
        ->assertOk()
        ->assertDontSee('English Only AG');
})->group('network');

it('shows published contact persons with their channels', function () {
    Network::factory()->create([
        'key' => 'iway',
        'locale' => LocaleEnum::DE->value,
        'name' => 'iWay',
        'category' => NetworkCategoryEnum::INFRASTRUCTURE->value,
    ]);

    NetworkUser::factory()->create([
        'network_key' => 'iway',
        'name' => 'Patrick Baumeler',
        'linkedin' => 'https://www.linkedin.com/in/example',
        'email' => 'patrick@example.com',
        'public_email' => 'patrick@example.com',
        'phone' => '+41 44 000 00 00',
        'published' => true,
    ]);

    get(route('de-ch.network.index'))
        ->assertOk()
        ->assertSee('Patrick Baumeler')
        ->assertSee('LinkedIn')
        ->assertSee('mailto:patrick@example.com', escape: false)
        ->assertSee('tel:+41440000000', escape: false);
})->group('network');

it('does not show unpublished contact persons', function () {
    Network::factory()->create([
        'key' => 'iway',
        'locale' => LocaleEnum::DE->value,
        'name' => 'iWay',
    ]);

    NetworkUser::factory()->create([
        'network_key' => 'iway',
        'name' => 'Unpublished Person',
        'published' => false,
    ]);

    get(route('de-ch.network.index'))
        ->assertOk()
        ->assertDontSee('Unpublished Person');
})->group('network');

it('links to the subpage only when a page slug is set', function () {
    Network::factory()->create([
        'key' => 'baselhack',
        'locale' => LocaleEnum::DE->value,
        'name' => 'BaselHack',
        'category' => NetworkCategoryEnum::SPONSORING->value,
        'page_slug' => 'baselhack',
    ]);

    Network::factory()->create([
        'key' => 'odoo',
        'locale' => LocaleEnum::DE->value,
        'name' => 'Odoo',
        'category' => NetworkCategoryEnum::SOFTWARE->value,
        'page_slug' => null,
    ]);

    get(route('de-ch.network.index'))
        ->assertOk()
        ->assertSee(route('de-ch.network.show', ['slug' => 'baselhack']), escape: false);
})->group('network');
