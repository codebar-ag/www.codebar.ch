<?php

use App\Enums\LocaleEnum;
use App\Models\Service;
use Database\Seeders\ServicesTableSeeder;

use function Pest\Laravel\seed;

it('seeds the four services in both locales', function () {
    seed(ServicesTableSeeder::class);

    $expectedSlugs = [
        'konzeption-prototyping',
        'individuelle-softwareentwicklung',
        'dms-ecm-consulting',
        'open-source-erp',
    ];

    foreach ($expectedSlugs as $slug) {
        foreach (LocaleEnum::cases() as $locale) {
            expect(Service::where('slug', $slug)->where('locale', $locale->value)->exists())->toBeTrue();
        }
    }

    expect(Service::count())->toBe(count($expectedSlugs) * count(LocaleEnum::cases()))
        ->and(Service::where('published', true)->count())->toBe(Service::count());
})->group('services', 'seeders');

it('seeds name, teaser, content and locale references', function () {
    seed(ServicesTableSeeder::class);

    $concept = Service::where('slug', 'konzeption-prototyping')->where('locale', LocaleEnum::DE->value)->firstOrFail();

    expect($concept->name)->toBe('Konzeption & Prototyping')
        ->and($concept->teaser)->toBe('Wir bringen deine Idee aufs Papier – visuell. Mit Mockups und klickbaren Prototypen entsteht früh ein gemeinsames Bild, noch bevor die erste Zeile Code geschrieben ist. Am Ende hältst du ein technisches Konzept in der Hand, inklusive Technologiewahl – als Basis für die Umsetzung, bei uns oder bei anderen.')
        ->and($concept->content)->not->toBeEmpty()
        ->and($concept->references()->count())->toBe(count(LocaleEnum::cases()));
})->group('services', 'seeders');

it('is idempotent', function () {
    seed(ServicesTableSeeder::class);
    seed(ServicesTableSeeder::class);

    expect(Service::count())->toBe(8);
})->group('services', 'seeders');
