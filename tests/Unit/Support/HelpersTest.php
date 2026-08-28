<?php

declare(strict_types=1);

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\App;

function bindCurrentRequestRoute(?string $name): void
{
    $request = createRequest('GET', '/helpers-test');

    $route = new RoutingRoute(['GET'], '/helpers-test', $name === null ? [] : ['as' => $name]);
    $route->bind($request);

    $request->setRouteResolver(fn (): RoutingRoute => $route);

    app()->instance('request', $request);
}

it('builds a localized route url using the current app locale', function () {
    App::setLocale('de_CH');

    expect(localized_route('start.index'))->toBe(route('de-ch.start.index', [], true));
})->group('unit', 'support');

it('builds a localized route url for an overridden locale', function () {
    expect(localized_route('start.index', [], true, 'en_CH'))->toBe(route('en-ch.start.index', [], true));
})->group('unit', 'support');

it('switches the current named route to the other locale', function () {
    bindCurrentRequestRoute('de-ch.services.index');

    expect(locale_switch_url('en_CH'))->toBe(route('en-ch.services.index'));
})->group('unit', 'support');

it('falls back to the start page when the current route has no name', function () {
    bindCurrentRequestRoute(null);

    expect(locale_switch_url('en_CH'))->toBe(route('en-ch.start.index'));
})->group('unit', 'support');

it('falls back to the start page when the target locale has no counterpart route', function () {
    bindCurrentRequestRoute('de-ch.only-in-german.index');

    expect(locale_switch_url('en_CH'))->toBe(route('en-ch.start.index'));
})->group('unit', 'support');

it('switches the current zunscan route to the other locale', function () {
    App::setLocale('de_CH');
    bindCurrentRequestRoute('zunscan.de-ch.about.index');

    expect(zunscan_locale_switch_url('en_CH'))->toBe('http://localhost'.route('zunscan.en-ch.about.index', absolute: false));
})->group('unit', 'support');

it('falls back to the zunscan start page when the current route is not a zunscan route', function () {
    App::setLocale('de_CH');
    bindCurrentRequestRoute('de-ch.services.index');

    expect(zunscan_locale_switch_url('en_CH'))->toBe('http://localhost'.route('zunscan.en-ch.start.index', absolute: false));
})->group('unit', 'support');

it('falls back to the zunscan start page when the target locale has no counterpart route', function () {
    App::setLocale('de_CH');
    bindCurrentRequestRoute('zunscan.de-ch.only-in-german.index');

    expect(zunscan_locale_switch_url('en_CH'))->toBe('http://localhost'.route('zunscan.en-ch.start.index', absolute: false));
})->group('unit', 'support');
