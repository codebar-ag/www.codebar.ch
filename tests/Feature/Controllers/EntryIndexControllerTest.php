<?php

declare(strict_types=1);

use App\Enums\CookieNameEnum;
use App\Enums\LocaleEnum;
use App\Enums\SessionKeyEnum;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\get;

function findEntryCookie(Response $response): ?Cookie
{
    foreach ($response->headers->getCookies() as $cookie) {
        if ($cookie->getName() === CookieNameEnum::ENTRY_REDIRECT->value) {
            return $cookie;
        }
    }

    return null;
}

function entryCookie(Response $response): Cookie
{
    return findEntryCookie($response) ?? throw new RuntimeException('The response carries no entry marker.');
}

it('redirects to the correct localized start page', function () {
    $locale = LocaleEnum::DE->value;
    App::setLocale($locale);

    $response = get('/');

    $expectedRoute = route(Str::slug($locale).'.start.index');

    $response->assertRedirect($expectedRoute);
})->group('entry');

it('redirects permanently, whatever the session language', function () {
    App::setLocale(LocaleEnum::EN->value);
    session([SessionKeyEnum::LANGUAGE->value => LocaleEnum::EN->value]);

    $response = get('/');

    $response->assertStatus(301);
    $response->assertRedirect(route(Str::slug(LocaleEnum::DE->value).'.start.index'));
})->group('entry');

it('marks the arrival with a cookie the start page can read', function () {
    $cookie = entryCookie(get('/')->baseResponse);

    expect($cookie->getValue())->toBe('1');
    expect($cookie->isHttpOnly())->toBeFalse();
    expect($cookie->getPath())->toBe('/');
    expect($cookie->getSameSite())->toBe('lax');
})->group('entry');

it('keeps the browser from caching the redirect, which would swallow the marker', function () {
    $response = get('/');

    expect($response->headers->get('cache-control'))->toContain('no-store');
})->group('entry');

it('keeps the browser from caching the redirect even once the response cache serves it', function () {
    Config::set('responsecache.enabled', true);

    get('/');
    $second = get('/');

    $second->assertHeader('x-cache-status', 'HIT');

    expect($second->headers->get('cache-control'))->toContain('no-store');
})->group('entry');

it('leaves the marker unencrypted so JavaScript can read it', function () {
    get('/')->assertPlainCookie(CookieNameEnum::ENTRY_REDIRECT->value, '1');
})->group('entry');

it('lets the marker expire within minutes', function () {
    $expires = entryCookie(get('/')->baseResponse)->getExpiresTime();

    expect($expires)->toBeGreaterThan(time());
    expect($expires)->toBeLessThanOrEqual(time() + 600);
})->group('entry');

it('keeps handing out the marker once the response cache serves the redirect', function () {
    Config::set('responsecache.enabled', true);

    $first = get('/');
    $second = get('/');

    $second->assertRedirect(route(Str::slug(LocaleEnum::DE->value).'.start.index'));
    $second->assertHeader('x-cache-status', 'HIT');

    expect(entryCookie($first->baseResponse)->getValue())->toBe('1');
    expect(entryCookie($second->baseResponse)->getValue())->toBe('1');
})->group('entry');

it('does not mark a direct visit to a localized start page', function (string $locale) {
    $response = get(route(Str::slug($locale).'.start.index'));

    $response->assertOk();

    expect(findEntryCookie($response->baseResponse))->toBeNull();
})->with([[LocaleEnum::DE->value], [LocaleEnum::EN->value]])->group('entry');

it('does not mark a visit to any other page', function () {
    $response = get(route(Str::slug(LocaleEnum::DE->value).'.about-us.index'));

    $response->assertOk();

    expect(findEntryCookie($response->baseResponse))->toBeNull();
})->group('entry');
