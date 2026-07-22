<?php

use App\Models\NetworkUser;
use Spatie\ResponseCache\Facades\ResponseCache;

it('clears the response cache when a network user is saved', function () {
    ResponseCache::shouldReceive('clear')->once();

    NetworkUser::factory()->create();
})->group('network');

it('clears the response cache when a network user is deleted', function () {
    $networkUser = NetworkUser::factory()->create();

    ResponseCache::shouldReceive('clear')->once();

    $networkUser->delete();
})->group('network');
