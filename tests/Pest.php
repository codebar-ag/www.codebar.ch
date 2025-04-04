<?php

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use NovaTesting\NovaAssertions;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class, NovaAssertions::class)
    ->beforeEach(function () {
        $this->seed(RolesAndPermissionsSeeder::class);
    })
    ->in(__DIR__);

function createRequest($method, $uri): Request
{
    $symfonyRequest = SymfonyRequest::create(
        $uri,
        $method
    );

    return Request::createFromBase($symfonyRequest);
}
