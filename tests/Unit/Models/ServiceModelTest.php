<?php

declare(strict_types=1);

use App\Models\Service;

test('create a Service model', function () {
    $model = Service::factory()->create();
    expect($model)->toBeInstanceOf(Service::class);
})->group('unit', 'models');

test('delete a Service model', function () {
    $model = Service::factory()->create();
    expect($model->delete())->toBeTrue();
})->group('unit', 'models');

it('resolves the route key name to slug', function () {
    $model = Service::factory()->create();
    expect($model->getRouteKeyName())->toBe('slug');
})->group('unit', 'models');

it('translates name, teaser and content per locale', function () {
    $service = Service::factory()->create([
        'name' => ['de_CH' => 'Name DE', 'en_CH' => 'Name EN'],
        'teaser' => ['de_CH' => 'Teaser DE', 'en_CH' => 'Teaser EN'],
    ]);

    expect($service->getTranslation('name', 'de_CH'))->toBe('Name DE')
        ->and($service->getTranslation('name', 'en_CH'))->toBe('Name EN')
        ->and($service->getTranslation('teaser', 'de_CH'))->toBe('Teaser DE')
        ->and($service->getTranslation('teaser', 'en_CH'))->toBe('Teaser EN');
})->group('unit', 'models');
