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
    $response->assertSee('Wir bieten dir folgende Lösungen an');
    $response->assertSee('Kontakt');
})->group('zunscan');

it('renders english content on the en-ch routes', function () {
    $response = get('http://zunscan.codebar.ch/en-ch');

    $response->assertOk();
    $response->assertSee('Here is what we can do for you');
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
        ->assertSee('<title>Kontakt und Standorte | zunscan.ch</title>', false);
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

it('shows one location card per address', function () {
    $html = (string) get('http://zunscan.codebar.ch/de-ch/kontakt')->assertOk()->getContent();

    expect($html)->toContain('Langegasse 39');   // codebar, Oberwil
    expect($html)->toContain('Hauptstrasse 91'); // Real Estate Club, Zunzgen

    // codebar's Zunzgen address was withdrawn — the company must appear exactly
    // once in the locations grid.
    expect(substr_count($html, 'CH-4455 Zunzgen'))->toBe(1);
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

it('gives every page a descriptive title within the search-result limit', function () {
    // "Herzlich Willkommen" told a crawler nothing about the business. Titles
    // have to lead with the service, and still fit a result snippet.
    foreach (['/de-ch', '/de-ch/services/scanning', '/de-ch/kontakt', '/en-ch', '/en-ch/services/scanning'] as $path) {
        $html = (string) get('http://zunscan.codebar.ch'.$path)->assertOk()->getContent();

        preg_match('/<title>(.*?)<\/title>/s', $html, $matches);
        $title = $matches[1] ?? '';

        expect($title)->toEndWith('| zunscan.ch');
        expect(mb_strlen($title))->toBeLessThanOrEqual(60);
        expect($title)->not->toContain('Herzlich Willkommen');
    }
})->group('zunscan');

it('keeps descriptions inside the length social previews render', function (string $path) {
    $html = (string) get('http://zunscan.codebar.ch'.$path)->assertOk()->getContent();

    preg_match('/<meta name="description" content="([^"]*)"/', $html, $matches);

    expect(mb_strlen($matches[1] ?? ''))->toBeGreaterThan(50)->toBeLessThanOrEqual(125);
})->with(['/de-ch', '/de-ch/about', '/de-ch/kontakt', '/en-ch', '/en-ch/about'])->group('zunscan');

it('ships a share card sized as the markup declares', function () {
    $html = (string) get('http://zunscan.codebar.ch/de-ch')->assertOk()->getContent();

    expect($html)->toContain('og-zunscan-de.jpg');
    expect($html)->toContain('<meta property="og:image:width" content="1200">');
    expect($html)->toContain('<meta property="og:image:height" content="630">');
    expect($html)->toContain('name="twitter:card" content="summary_large_image"');

    // Both files must exist at the declared 1200×630, or the dimensions lie.
    foreach (['de', 'en'] as $locale) {
        $file = public_path("images/seo/og-zunscan-{$locale}.jpg");
        expect(file_exists($file))->toBeTrue();
        expect(getimagesize($file))->toMatchArray([0 => 1200, 1 => 630]);
    }
})->group('zunscan');

it('serves the english share card on english pages', function () {
    get('http://zunscan.codebar.ch/en-ch')->assertOk()->assertSee('og-zunscan-en.jpg', false);
})->group('zunscan');

it('links a complete favicon set', function () {
    $html = (string) get('http://zunscan.codebar.ch/de-ch')->assertOk()->getContent();

    foreach (['favicon-96x96.png', 'favicon.svg', 'apple-touch-icon.png', 'site.webmanifest'] as $asset) {
        expect($html)->toContain("favicons/zunscan/{$asset}");
        expect(file_exists(public_path("favicons/zunscan/{$asset}")))->toBeTrue();
    }
})->group('zunscan');

it('describes the business as a local business with a breadcrumb on inner pages', function () {
    $html = (string) get('http://zunscan.codebar.ch/de-ch/kontakt')->assertOk()->getContent();

    preg_match('/<script type="application\/ld\+json">(.+?)<\/script>/s', $html, $matches);
    $decoded = json_decode($matches[1] ?? '', true, flags: JSON_THROW_ON_ERROR);
    $nodes = is_array($decoded) && is_array($decoded['@graph'] ?? null) ? $decoded['@graph'] : [];

    expect(array_column($nodes, '@type'))
        ->toContain('Organization', 'LocalBusiness', 'WebSite', 'WebPage', 'BreadcrumbList');
})->group('zunscan');

it('omits the breadcrumb on the start page', function () {
    $html = (string) get('http://zunscan.codebar.ch/de-ch')->assertOk()->getContent();

    // A single self-referential crumb is noise; Google ignores it either way.
    expect($html)->not->toContain('BreadcrumbList');
})->group('zunscan');

it('publishes the current price architecture in both languages', function (string $path) {
    $response = get('http://zunscan.codebar.ch'.$path)->assertOk();

    foreach ([
        'CHF 64.50', 'CHF 61.25', 'CHF 58.25', 'CHF 55.75', // volume tiers
        'CHF 0.22',                                          // loose documents
        'CHF 499.00',                                        // setup package
        'CHF 2.50',                                          // disposal
        'CHF 25.00', 'CHF 108.50', 'CHF 0.31',               // return, both variants
        'CHF 95.00 / h', 'CHF 205.00 / h',                   // hourly rates
        '8.1',                                               // VAT disclosure
    ] as $needle) {
        $response->assertSee($needle);
    }
})->with(['/de-ch/services/scanning', '/en-ch/services/scanning'])->group('zunscan');

it('retires every superseded price', function (string $path) {
    $html = (string) get('http://zunscan.codebar.ch'.$path)->assertOk()->getContent();

    // Each of these was replaced, and each appeared in more than one block —
    // the per-kg disposal line in particular also sat in the two scanning cards.
    foreach (['CHF 0.19', 'CHF 1.50 / kg', 'CHF 250.00', 'CHF 90.00 / h', 'CHF 165.00', 'CHF 4.50'] as $retired) {
        expect($html)->not->toContain($retired);
    }
})->with(['/de-ch/services/scanning', '/en-ch/services/scanning'])->group('zunscan');

it('offers the free trial scan above the price tables', function (string $path, string $contact) {
    $html = (string) get('http://zunscan.codebar.ch'.$path)->assertOk()->getContent();

    expect(strpos($html, $contact))->toBeLessThan((int) strpos($html, 'CHF 64.50'));
})->with([
    ['/de-ch/services/scanning', '/de-ch/kontakt'],
    ['/en-ch/services/scanning', '/en-ch/contact'],
])->group('zunscan');

it('no longer offers a services dropdown label', function () {
    $html = (string) get('http://zunscan.codebar.ch/de-ch')->assertOk()->getContent();

    expect($html)->not->toContain('Dienstleistungen');
    expect($html)->toContain('Digitalisierung');
})->group('zunscan');
