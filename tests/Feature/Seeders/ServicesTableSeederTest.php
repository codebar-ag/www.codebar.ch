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
        $service = Service::where('slug', $slug)->firstOrFail();

        foreach (LocaleEnum::cases() as $locale) {
            expect($service->getTranslation('name', $locale->value, false))->not->toBeEmpty();
        }
    }

    expect(Service::count())->toBe(count($expectedSlugs))
        ->and(Service::where('published', true)->count())->toBe(Service::count());
})->group('services', 'seeders');

it('seeds name, teaser and content per locale', function () {
    seed(ServicesTableSeeder::class);

    $concept = Service::where('slug', 'konzeption-prototyping')->firstOrFail();

    expect($concept->getTranslation('name', LocaleEnum::DE->value))->toBe('Konzeption & Prototyping')
        ->and($concept->getTranslation('teaser', LocaleEnum::DE->value))->toBe('Wir bringen deine Idee aufs Papier – visuell. Mit Mockups und klickbaren Prototypen entsteht früh ein gemeinsames Bild, noch bevor die erste Zeile Code geschrieben ist. Am Ende hältst du ein technisches Konzept in der Hand, inklusive Technologiewahl – als Basis für die Umsetzung, bei uns oder bei anderen.')
        ->and($concept->getTranslation('content', LocaleEnum::DE->value))->not->toBeEmpty()
        ->and($concept->getTranslation('name', LocaleEnum::EN->value))->toBe('Concept design & prototyping');
})->group('services', 'seeders');

it('is idempotent', function () {
    seed(ServicesTableSeeder::class);
    seed(ServicesTableSeeder::class);

    expect(Service::count())->toBe(4);
})->group('services', 'seeders');
