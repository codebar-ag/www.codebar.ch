<?php

use function Pest\Laravel\get;

it('returns the matrix server well-known response', function () {
    get('.well-known/matrix/server')
        ->assertOk()
        ->assertExactJson([
            'm.server' => 'connect.codebar.ch:443',
        ]);
});

it('returns the matrix client well-known response with CORS header', function () {
    get('.well-known/matrix/client')
        ->assertOk()
        ->assertExactJson([
            'm.homeserver' => ['base_url' => 'https://connect.codebar.ch'],
            'm.identity_server' => ['base_url' => 'https://vector.im'],
        ])
        ->assertHeader('Access-Control-Allow-Origin', '*');
});
