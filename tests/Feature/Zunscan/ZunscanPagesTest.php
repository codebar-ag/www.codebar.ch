<?php

declare(strict_types=1);

use function Pest\Laravel\get;

it('redirects the domain root to the german start page', function () {
    get('http://zunscan.codebar.ch/')
        ->assertRedirect('https://zunscan.codebar.ch/de-ch');
})->group('zunscan');

it('serves every zunscan.codebar.ch page in german', function (string $path) {
    get('http://zunscan.codebar.ch'.$path)->assertOk();
})->with([
    '/de-ch',
    '/de-ch/about',
    '/de-ch/services/scanning',
    '/de-ch/kontakt',
    '/de-ch/medien',
    '/de-ch/impressum',
    '/de-ch/datenschutz',
])->group('zunscan');

it('serves every zunscan.codebar.ch page in english', function (string $path) {
    get('http://zunscan.codebar.ch'.$path)->assertOk();
})->with([
    '/en-ch',
    '/en-ch/about',
    '/en-ch/services/scanning',
    '/en-ch/contact',
    '/en-ch/media',
    '/en-ch/imprint',
    '/en-ch/privacy',
])->group('zunscan');

it('does not publish the epost service', function () {
    get('http://zunscan.codebar.ch/de-ch/services/epost')->assertNotFound();
    get('http://zunscan.codebar.ch/en-ch/services/epost')->assertNotFound();
})->group('zunscan');

it('renders german content on the de-ch routes', function () {
    $response = get('http://zunscan.codebar.ch/de-ch');

    $response->assertOk();
    $response->assertSee('Herzlich Willkommen');
    $response->assertSee('Kontakt');
})->group('zunscan');

it('renders english content on the en-ch routes', function () {
    $response = get('http://zunscan.codebar.ch/en-ch');

    $response->assertOk();
    $response->assertSee('Welcome');
    $response->assertSee('Contact');
})->group('zunscan');

it('does not serve zunscan pages on the main domain', function () {
    get('http://web.codebar.test/services/scanning')->assertNotFound();
})->group('zunscan');

it('serves a zunscan-specific robots.txt', function () {
    app()->detectEnvironment(fn (): string => 'production');

    $response = get('http://zunscan.codebar.ch/robots.txt');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    expect($response->getContent())->toContain('Sitemap: http://zunscan.codebar.ch/sitemap.xml');
})->group('zunscan');

it('serves a zunscan-specific sitemap scoped to its own domain and both locales', function () {
    $response = get('http://zunscan.codebar.ch/sitemap.xml');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/xml');

    $content = $response->getContent();

    expect($content)->toContain('http://zunscan.codebar.ch/de-ch');
    expect($content)->toContain('http://zunscan.codebar.ch/en-ch');
    expect($content)->not->toContain('services/epost');
    expect($content)->not->toContain('www.codebar.ch');
})->group('zunscan');

it('links to the other language via the locale switcher', function () {
    $response = get('http://zunscan.codebar.ch/de-ch/kontakt');

    $response->assertOk();
    $response->assertSee('href="http://zunscan.codebar.ch/en-ch/contact"', false);
})->group('zunscan');

it('links back to the german page from its english counterpart', function () {
    $response = get('http://zunscan.codebar.ch/en-ch/contact');

    $response->assertOk();
    $response->assertSee('href="http://zunscan.codebar.ch/de-ch/kontakt"', false);
})->group('zunscan');

it('serves zunscan CSS and JS from the requesting host, not the main site domain', function () {
    $response = get('http://zunscan.codebar.ch/de-ch');

    $response->assertOk();
    $response->assertSee('zunscan.codebar.ch/build/assets/', false);
    $response->assertDontSee((string) parse_url(config()->string('app.url'), PHP_URL_HOST), false);
})->group('zunscan');

it('renders a canonical url on the requesting host', function () {
    $response = get('http://zunscan.codebar.ch/de-ch/kontakt');

    $response->assertOk();
    $response->assertSee('<link rel="canonical" href="http://zunscan.codebar.ch/de-ch/kontakt">', false);
})->group('zunscan');

it('points x-default at the german start page', function () {
    $response = get('http://zunscan.codebar.ch/en-ch');

    $response->assertOk();
    $response->assertSee('hreflang="x-default"', false);
    $response->assertSee('href="http://zunscan.codebar.ch/de-ch"', false);
})->group('zunscan');

it('suffixes the page title with the brand', function () {
    get('http://zunscan.codebar.ch/de-ch/kontakt')
        ->assertOk()
        ->assertSee('<title>Kontakt | zunscan.ch</title>', false);
})->group('zunscan');

it('emits a valid json-ld graph describing the joint venture', function () {
    $html = (string) get('http://zunscan.codebar.ch/de-ch')->assertOk()->getContent();

    expect(preg_match('/<script type="application\/ld\+json">(.+?)<\/script>/s', $html, $matches))->toBe(1);

    $json = $matches[1] ?? '';

    // Throws if the payload is not parseable — a malformed graph is worse than
    // no graph, because it fails silently in the crawler rather than here.
    json_decode($json, true, flags: JSON_THROW_ON_ERROR);

    expect($json)
        ->toContain('"legalName":"paperflakes AG"')
        ->toContain('codebar Solutions AG')
        ->toContain('Real Estate Club GmbH');
})->group('zunscan');

it('shows both joint venture contacts with all four channels', function () {
    $response = get('http://zunscan.codebar.ch/de-ch/kontakt')->assertOk();

    foreach (array_keys(config()->array('zunscan.people')) as $index) {
        $response->assertSee(config()->string("zunscan.people.{$index}.name"));
        $response->assertSee(config()->string("zunscan.people.{$index}.company"));
        $response->assertSee('mailto:'.config()->string("zunscan.people.{$index}.email"), false);
        $response->assertSee('tel:'.str_replace(' ', '', config()->string("zunscan.people.{$index}.phone")), false);
        $response->assertSee(config()->string("zunscan.people.{$index}.website"), false);
        $response->assertSee(config()->string("zunscan.people.{$index}.linkedin"), false);
    }
})->group('zunscan');

it('states the correct company address on the imprint', function () {
    $response = get('http://zunscan.codebar.ch/de-ch/impressum')->assertOk();

    $response->assertSee('Hauptstrasse 91');
    $response->assertSee('CHE-432.585.498');
    // The old, wrong address. It lived in the markdown, the footer and the
    // JSON-LD at once, which is why it survived a correction before.
    $response->assertDontSee('Mühlematten');
})->group('zunscan');

it('never emits the old company address anywhere', function (string $path) {
    get('http://zunscan.codebar.ch'.$path)->assertOk()->assertDontSee('Mühlematten');
})->with(['/de-ch', '/de-ch/kontakt', '/de-ch/impressum', '/en-ch/imprint'])->group('zunscan');

it('lists mischa lanz before sebastian on the contact page', function () {
    $html = (string) get('http://zunscan.codebar.ch/de-ch/kontakt')->assertOk()->getContent();

    preg_match_all('/(Mischa Lanz|Sebastian Bürgin-Fix)/u', $html, $matches);

    expect($matches[1][0] ?? null)->toBe('Mischa Lanz');
})->group('zunscan');

it('shows both codebar locations', function () {
    get('http://zunscan.codebar.ch/de-ch/kontakt')
        ->assertOk()
        ->assertSee('Langegasse 39')
        ->assertSee('Hauptstrasse 91');
})->group('zunscan');

it('credits both owner companies in the footer and drops the contact column', function () {
    $html = (string) get('http://zunscan.codebar.ch/de-ch')->assertOk()->getContent();

    preg_match('/<footer.*?<\/footer>/s', $html, $matches);
    $footer = $matches[0] ?? '';

    expect($footer)->toContain('Real Estate Club GmbH');
    expect($footer)->toContain('codebar Solutions AG');
    // The contact column is gone. Asserted on the footer alone, because the
    // address and e-mail still belong in the JSON-LD graph in <head>.
    expect($footer)->not->toContain('mailto:');
})->group('zunscan');

it('no longer offers a services dropdown label', function () {
    $html = (string) get('http://zunscan.codebar.ch/de-ch')->assertOk()->getContent();

    expect($html)->not->toContain('Dienstleistungen');
    expect($html)->toContain('Digitalisierung');
})->group('zunscan');
