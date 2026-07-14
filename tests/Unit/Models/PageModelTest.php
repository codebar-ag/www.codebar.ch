<?php

use App\Models\Page;

test('create a Page model', function () {
    $model = Page::create([
        'key' => 'start.index',
        'locale' => 'de_CH',
        'robots' => 'index,follow',
        'title' => 'Start',
        'description' => 'Start page description',
    ]);

    expect($model)->toBeInstanceOf(Page::class);
})->group('unit', 'models');

test('delete a Page model', function () {
    $model = Page::create([
        'key' => 'start.index',
        'locale' => 'de_CH',
        'robots' => 'index,follow',
        'title' => 'Start',
        'description' => 'Start page description',
    ]);

    $this->assertTrue($model->delete());
})->group('unit', 'models');
