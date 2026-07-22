<?php

use App\Models\User;

use function Pest\Laravel\assertSoftDeleted;

it('can create a user model', function () {
    $model = User::factory()->create();
    expect($model)->toBeInstanceOf(User::class);
})->group('unit', 'models');

it('can soft delete a user', function () {
    $model = User::factory()->create();
    $model->delete();
    assertSoftDeleted($model);
})->group('unit', 'models');

it('can delete a user', function () {
    $model = User::factory()->create();
    expect($model->forceDelete())->toBeTrue();
})->group('unit', 'models');

it('can get a user gravatar url', function () {
    $model = User::factory()->create();
    expect($model->getGravatarUrl())->toBe('https://www.gravatar.com/avatar/'.md5($model->email).'?s=200&d=mp');
    expect($model->getGravatarUrl(400))->toBe('https://www.gravatar.com/avatar/'.md5($model->email).'?s=400&d=mp');
})->group('unit', 'models');
