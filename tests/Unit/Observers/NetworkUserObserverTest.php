<?php

use App\Models\NetworkUser;
use Spatie\ResponseCache\Facades\ResponseCache;

it('clears the response cache when a network user is saved', function () {
    ResponseCache::spy();

    NetworkUser::factory()->create();

    ResponseCache::shouldHaveReceived('clear')->once();
})->group('network');

it('clears the response cache when a network user is deleted', function () {
    $networkUser = NetworkUser::factory()->create();

    ResponseCache::spy();

    $networkUser->delete();

    ResponseCache::shouldHaveReceived('clear')->once();
})->group('network');
