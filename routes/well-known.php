<?php

use Illuminate\Support\Facades\Route;

Route::get('.well-known/matrix/server', function () {
    return response()->json([
        'm.server' => 'connect.codebar.ch:443',
    ]);
});

Route::get('.well-known/matrix/client', function () {
    return response()->json([
        'm.homeserver' => ['base_url' => 'https://connect.codebar.ch'],
        'm.identity_server' => ['base_url' => 'https://vector.im'],
    ])->header('Access-Control-Allow-Origin', '*');
});
