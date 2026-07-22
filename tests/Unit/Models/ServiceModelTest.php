<?php

use App\Models\Service;

test('create a Service model', function () {
    $model = Service::factory()->create();
    expect($model)->toBeInstanceOf(Service::class);
})->group('unit', 'models');

test('delete a Service model', function () {
    $model = Service::factory()->create();
    $this->assertTrue($model->delete());
})->group('unit', 'models');

it('resolves the route key name to slug', function () {
    $model = Service::factory()->create();
    expect($model->getRouteKeyName())->toBe('slug');
})->group('unit', 'models');

it('has a references relation', function () {
    $service = Service::factory()->create();
    $other = Service::factory()->create();

    $service->references()->create([
        'reference_type' => Service::class,
        'reference_id' => $other->id,
        'reference_locale' => $other->locale->value,
    ]);

    expect($service->references()->count())->toBe(1);
    expect($service->references->first()->target)->toBeInstanceOf(Service::class);
})->group('unit', 'models');
