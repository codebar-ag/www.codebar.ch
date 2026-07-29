<?php

declare(strict_types=1);

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

it('translates title, teaser and content per locale', function () {
    $openSource = OpenSource::factory()->create([
        'title' => ['de_CH' => 'Titel DE', 'en_CH' => 'Title EN'],
        'teaser' => ['de_CH' => 'Teaser DE', 'en_CH' => 'Teaser EN'],
    ]);

    expect($openSource->getTranslation('title', 'de_CH'))->toBe('Titel DE')
        ->and($openSource->getTranslation('title', 'en_CH'))->toBe('Title EN')
        ->and($openSource->getTranslation('teaser', 'de_CH'))->toBe('Teaser DE')
        ->and($openSource->getTranslation('teaser', 'en_CH'))->toBe('Teaser EN');
})->group('unit', 'models');
