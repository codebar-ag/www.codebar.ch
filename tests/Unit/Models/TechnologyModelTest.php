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

it('translates title, teaser and content per locale', function () {
    $technology = Technology::factory()->create([
        'title' => ['de_CH' => 'Titel DE', 'en_CH' => 'Title EN'],
        'teaser' => ['de_CH' => 'Teaser DE', 'en_CH' => 'Teaser EN'],
    ]);

    expect($technology->getTranslation('title', 'de_CH'))->toBe('Titel DE')
        ->and($technology->getTranslation('title', 'en_CH'))->toBe('Title EN')
        ->and($technology->getTranslation('teaser', 'de_CH'))->toBe('Teaser DE')
        ->and($technology->getTranslation('teaser', 'en_CH'))->toBe('Teaser EN');
})->group('unit', 'models');
