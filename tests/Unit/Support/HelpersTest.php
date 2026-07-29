<?php

declare(strict_types=1);

use Illuminate\Support\Facades\App;

it('builds a localized route url using the current app locale', function () {
    App::setLocale('de_CH');

    expect(localized_route('start.index'))->toBe(route('de-ch.start.index', [], true));
})->group('unit', 'support');

it('builds a localized route url for an overridden locale', function () {
    expect(localized_route('start.index', [], true, 'en_CH'))->toBe(route('en-ch.start.index', [], true));
})->group('unit', 'support');
