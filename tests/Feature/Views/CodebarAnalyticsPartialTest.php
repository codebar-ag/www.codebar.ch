<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;

it('embeds the codebar analytics script in production', function () {
    Config::set('default.services.codebar_analytics.url', 'https://analytics.codebar.ch');
    app()['env'] = 'production';

    expect(view('layouts._partials._codebar_analytics')->render())
        ->toContain('<script src="https://analytics.codebar.ch/js/script.js" crossorigin="anonymous" defer></script>');
})->group('analytics');

it('does not embed the codebar analytics script outside production', function () {
    Config::set('default.services.codebar_analytics.url', 'https://analytics.codebar.ch');

    expect(view('layouts._partials._codebar_analytics')->render())->not->toContain('analytics.codebar.ch');
})->group('analytics');

it('does not embed the codebar analytics script without a url', function () {
    Config::set('default.services.codebar_analytics.url', null);
    app()['env'] = 'production';

    expect(view('layouts._partials._codebar_analytics')->render())->not->toContain('<script');
})->group('analytics');

it('embeds the codebar analytics script on zunscan.codebar.ch in production', function () {
    $this->withoutVite();
    Config::set('default.services.codebar_analytics.url', 'https://analytics.codebar.ch');
    app()->detectEnvironment(fn () => 'production');

    $this->get('http://zunscan.codebar.ch/de-ch')
        ->assertOk()
        ->assertSee('<script src="https://analytics.codebar.ch/js/script.js" crossorigin="anonymous" defer></script>', false);
})->group('analytics');
