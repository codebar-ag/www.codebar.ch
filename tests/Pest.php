<?php

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Testing\PendingCommand;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Tests\TestCase;

use function Pest\Laravel\artisan;
use function Pest\Laravel\seed;

uses(TestCase::class, RefreshDatabase::class)
    ->beforeEach(function () {
        seed(RolesAndPermissionsSeeder::class);
    })
    ->in(__DIR__);

function createRequest(string $method, string $uri): Request
{
    $symfonyRequest = SymfonyRequest::create(
        $uri,
        $method
    );

    return Request::createFromBase($symfonyRequest);
}

/**
 * Typed wrapper around the artisan() helper: the console-testing chain is
 * only available on PendingCommand, never on the bare exit code.
 *
 * @param  array<string, mixed>  $parameters
 */
function runArtisan(string $command, array $parameters = []): PendingCommand
{
    $result = artisan($command, $parameters);

    assert($result instanceof PendingCommand);

    return $result;
}
