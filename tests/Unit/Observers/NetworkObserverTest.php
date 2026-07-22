<?php

use App\Models\Network;
use Spatie\ResponseCache\Facades\ResponseCache;

it('clears the response cache when a network is saved', function () {
    ResponseCache::spy();

    Network::factory()->create();

    ResponseCache::shouldHaveReceived('clear')->once();
})->group('network');

it('clears the response cache when a network is deleted', function () {
    $network = Network::factory()->create();

    ResponseCache::spy();

    $network->delete();

    ResponseCache::shouldHaveReceived('clear')->once();
})->group('network');
