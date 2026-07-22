<?php

use App\Enums\LocaleEnum;
use App\Enums\SessionKeyEnum;
use App\Models\Service;
use Illuminate\Support\Str;

use function Pest\Laravel\get;
use function Pest\Laravel\withSession;

it('returns 200 for both locales', function (string $locale) {
    withSession([SessionKeyEnum::LANGUAGE->value => $locale])
        ->get(route(Str::slug($locale).'.services.index'))
        ->assertOk();
})->with([LocaleEnum::DE->value, LocaleEnum::EN->value])->group('services');

it('shows a published service with name and teaser', function () {
    Service::factory()->create([
        'locale' => LocaleEnum::DE->value,
        'name' => 'Konzeption Visible',
        'teaser' => 'Sichtbarer Teaser Text',
        'published' => true,
    ]);

    get(route('de-ch.services.index'))
        ->assertOk()
        ->assertSee('Konzeption Visible')
        ->assertSee('Sichtbarer Teaser Text');
})->group('services');

it('does not show unpublished services', function () {
    Service::factory()->create([
        'locale' => LocaleEnum::DE->value,
        'name' => 'Hidden Service XYZ',
        'published' => false,
    ]);

    get(route('de-ch.services.index'))
        ->assertOk()
        ->assertDontSee('Hidden Service XYZ');
})->group('services');

it('does not show services of another locale', function () {
    Service::factory()->create([
        'locale' => LocaleEnum::EN->value,
        'name' => 'English Only Service',
        'published' => true,
    ]);

    get(route('de-ch.services.index'))
        ->assertOk()
        ->assertDontSee('English Only Service');
})->group('services');
