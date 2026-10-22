<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\NetworkUser;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\put;

/**
 * The signed manage/application routes bind models by their bigint primary key. Without a
 * numeric constraint, scanner probes such as /network/manage/.env reached the implicit
 * binding and Postgres rejected the lookup ("invalid input syntax for type bigint"), which
 * surfaced as a 500 instead of a 404.
 */
it('404s on a non-numeric model id', function (string $method, string $url) {
    $response = match ($method) {
        'get' => get($url),
        'put' => put($url),
        'patch' => patch($url),
        'delete' => delete($url),
    };

    $response->assertNotFound();
})->with([
    'network manage (en)' => ['get', '/network/manage/.env'],
    'network manage (de)' => ['get', '/netzwerk/verwalten/.env'],
    'network manage update (en)' => ['put', '/network/manage/.env'],
    'network manage update (de)' => ['put', '/netzwerk/verwalten/wp-config.php'],
    'application (en)' => ['get', '/jobs/internship/application/.env'],
    'application (de)' => ['get', '/stellen/praktikum/bewerbung/.env'],
    'application update' => ['put', '/jobs/internship/application/.git'],
    'application autosave' => ['patch', '/stellen/praktikum/bewerbung/abc'],
    'application file (de)' => ['delete', '/stellen/praktikum/bewerbung/.env/dateien/1'],
])->group('routes');

it('404s on a non-numeric application file id of an existing application', function () {
    $application = Application::factory()->create();

    delete("/jobs/internship/application/{$application->id}/files/.env")->assertNotFound();
})->group('routes');

it('still binds numeric ids on the signed manage routes', function () {
    $networkUser = NetworkUser::factory()->create();

    get(URL::temporarySignedRoute('en-ch.network.manage.show', now()->addHour(), ['networkUser' => $networkUser]))
        ->assertOk();
})->group('routes');
