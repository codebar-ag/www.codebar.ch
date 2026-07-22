<?php

use App\Models\OpenSource;

test('create a OpenSource model', function () {
    $model = OpenSource::factory()->create();
    expect($model)->toBeInstanceOf(OpenSource::class);
})->group('unit', 'models');

test('delete a OpenSource model', function () {
    $model = OpenSource::factory()->create();
    expect($model->delete())->toBeTrue();
})->group('unit', 'models');

it('resolves the route key name to slug', function () {
    $model = OpenSource::factory()->create();
    expect($model->getRouteKeyName())->toBe('slug');
})->group('unit', 'models');

it('has a references relation', function () {
    $openSource = OpenSource::factory()->create();
    $other = OpenSource::factory()->create();

    $openSource->references()->create([
        'reference_type' => OpenSource::class,
        'reference_id' => $other->id,
        'reference_locale' => $other->locale->value,
    ]);

    expect($openSource->references()->count())->toBe(1);
    expect($openSource->references->firstOrFail()->target)->toBeInstanceOf(OpenSource::class);
})->group('unit', 'models');
