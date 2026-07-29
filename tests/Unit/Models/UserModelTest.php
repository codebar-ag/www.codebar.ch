<?php

declare(strict_types=1);

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
