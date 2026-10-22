<?php

declare(strict_types=1);

use function Pest\Laravel\get;

it('serves the health check results to Oh Dear', function () {
    config(['health.oh_dear_endpoint.secret' => 'test-secret']);

    get('/oh-dear-health-check-results', ['oh-dear-health-check-secret' => 'test-secret'])
        ->assertOk()
        ->assertJsonStructure(['checkResults']);
})->group('feature', 'health');

it('rejects requests without the Oh Dear secret', function () {
    get('/oh-dear-health-check-results')->assertForbidden();
})->group('feature', 'health');
