<?php

declare(strict_types=1);

use App\Models\Page;

test('create a Page model', function () {
    $model = Page::create([
        'key' => 'start.index',
        'robots' => 'index,follow',
        'title' => ['de_CH' => 'Start', 'en_CH' => 'Start'],
        'description' => ['de_CH' => 'Start-Seiten-Beschreibung', 'en_CH' => 'Start page description'],
    ]);

    expect($model)->toBeInstanceOf(Page::class);
})->group('unit', 'models');

test('delete a Page model', function () {
    $model = Page::create([
        'key' => 'start.index',
        'robots' => 'index,follow',
        'title' => ['de_CH' => 'Start', 'en_CH' => 'Start'],
        'description' => ['de_CH' => 'Start-Seiten-Beschreibung', 'en_CH' => 'Start page description'],
    ]);

    expect($model->delete())->toBeTrue();
})->group('unit', 'models');

it('translates title and description per locale', function () {
    $model = Page::create([
        'key' => 'start.index',
        'robots' => 'index,follow',
        'title' => ['de_CH' => 'Titel DE', 'en_CH' => 'Title EN'],
        'description' => ['de_CH' => 'Beschreibung DE', 'en_CH' => 'Description EN'],
    ]);

    expect($model->getTranslation('title', 'de_CH'))->toBe('Titel DE')
        ->and($model->getTranslation('title', 'en_CH'))->toBe('Title EN')
        ->and($model->getTranslation('description', 'de_CH'))->toBe('Beschreibung DE')
        ->and($model->getTranslation('description', 'en_CH'))->toBe('Description EN');
})->group('unit', 'models');
