<?php

use App\Models\Network;
use Spatie\ResponseCache\Facades\ResponseCache;

it('clears the response cache when a network is saved', function () {
    ResponseCache::shouldReceive('clear')->once();

    Network::factory()->create();
})->group('network');

it('clears the response cache when a network is deleted', function () {
    $network = Network::factory()->create();

    ResponseCache::shouldReceive('clear')->once();

    $network->delete();
})->group('network');
