<?php

use App\Enums\LocaleEnum;
use App\Models\Service;
use Database\Seeders\ServicesTableSeeder;

it('seeds the three services in both locales', function () {
    $this->seed(ServicesTableSeeder::class);

    $expectedSlugs = [
        'konzeption-prototyping',
        'individuelle-softwareentwicklung',
        'dms-ecm-consulting',
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
    $this->seed(ServicesTableSeeder::class);

    $concept = Service::where('slug', 'konzeption-prototyping')->where('locale', LocaleEnum::DE->value)->first();

    expect($concept->name)->toBe('Konzeption & Prototyping')
        ->and($concept->teaser)->toBe('Von der ersten Idee über das Konzept zum klickbaren Prototyp.')
        ->and($concept->content)->not->toBeEmpty()
        ->and($concept->references()->count())->toBe(count(LocaleEnum::cases()));
})->group('services', 'seeders');

it('is idempotent', function () {
    $this->seed(ServicesTableSeeder::class);
    $this->seed(ServicesTableSeeder::class);

    expect(Service::count())->toBe(6);
})->group('services', 'seeders');
