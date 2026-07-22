<?php

use App\Models\Technology;

test('create a Technology model', function () {
    $model = Technology::factory()->create();
    expect($model)->toBeInstanceOf(Technology::class);
})->group('unit', 'models');

test('delete a Technology model', function () {
    $model = Technology::factory()->create();
    expect($model->delete())->toBeTrue();
})->group('unit', 'models');

it('resolves the route key name to slug', function () {
    $model = Technology::factory()->create();
    expect($model->getRouteKeyName())->toBe('slug');
})->group('unit', 'models');

it('has a references relation', function () {
    $technology = Technology::factory()->create();
    $other = Technology::factory()->create();

    $technology->references()->create([
        'reference_type' => Technology::class,
        'reference_id' => $other->id,
        'reference_locale' => $other->locale->value,
    ]);

    expect($technology->references()->count())->toBe(1);
    expect($technology->references->firstOrFail()->target)->toBeInstanceOf(Technology::class);
})->group('unit', 'models');
