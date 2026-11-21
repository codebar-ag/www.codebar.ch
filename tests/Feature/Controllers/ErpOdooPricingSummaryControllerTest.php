<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use Database\Seeders\PagesTableSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

/**
 * @param  array<mixed>  $query
 */
function summaryUrl(array $query = ['full' => 25, 'light' => 10, 'billing' => 'yearly'], string $locale = 'de-ch'): string
{
    return route($locale.'.erp.systems.pricing.summary', $query);
}

it('returns 200 for a complete configuration in both locales', function (string $locale) {
    get(summaryUrl(locale: Str::slug($locale)))->assertOk();
})->with([LocaleEnum::DE->value, LocaleEnum::EN->value])->group('odoo-pricing');

it('uses a translated path per locale', function () {
    expect(route('de-ch.erp.systems.pricing.summary', [], false))->toBe('/erp/odoo/preise/zusammenfassung')
        ->and(route('en-ch.erp.systems.pricing.summary', [], false))->toBe('/erp/odoo/pricing/summary');
})->group('odoo-pricing');

it('redirects an incomplete or invalid configuration to its normalised address', function (array $query, array $expected) {
    get(summaryUrl($query))->assertRedirect(summaryUrl($expected));
})->with([
    'nothing' => [[], ['full' => 10, 'light' => 0, 'billing' => 'yearly']],
    'missing billing' => [['full' => 25, 'light' => 10], ['full' => 25, 'light' => 10, 'billing' => 'yearly']],
    'text' => [['full' => 'abc', 'light' => 10, 'billing' => 'yearly'], ['full' => 10, 'light' => 10, 'billing' => 'yearly']],
    'negative' => [['full' => -3, 'light' => 2, 'billing' => 'yearly'], ['full' => 10, 'light' => 2, 'billing' => 'yearly']],
    'leading zero' => [['full' => '025', 'light' => 10, 'billing' => 'yearly'], ['full' => 25, 'light' => 10, 'billing' => 'yearly']],
    'above maximum' => [['full' => 500, 'light' => 0, 'billing' => 'yearly'], ['full' => 150, 'light' => 0, 'billing' => 'yearly']],
    'sum above maximum' => [['full' => 100, 'light' => 100, 'billing' => 'yearly'], ['full' => 100, 'light' => 50, 'billing' => 'yearly']],
    'nobody' => [['full' => 0, 'light' => 0, 'billing' => 'yearly'], ['full' => 1, 'light' => 0, 'billing' => 'yearly']],
    'unknown billing' => [['full' => 25, 'light' => 10, 'billing' => 'weekly'], ['full' => 25, 'light' => 10, 'billing' => 'yearly']],
    'array' => [['full' => ['1'], 'light' => 10, 'billing' => 'yearly'], ['full' => 10, 'light' => 10, 'billing' => 'yearly']],
])->group('odoo-pricing');

it('keeps unrelated parameters when it normalises the address', function () {
    get(summaryUrl(['full' => 'x', 'utm_source' => 'mail']))
        ->assertRedirect(summaryUrl(['full' => 10, 'utm_source' => 'mail', 'light' => 0, 'billing' => 'yearly']));
})->group('odoo-pricing');

it('shows every figure of a yearly configuration', function () {
    get(summaryUrl())
        ->assertOk()
        ->assertSeeInOrder([
            'Deine Odoo-Kosten',
            'Berechnet für 25 Benutzer mit Vollzugriff und 10 Light-Benutzer bei jährlicher Abrechnung.',
            "CHF 16'050.00",
            "CHF 1'337.50",
            "CHF 14'418.00",
            "CHF 1'632.00",
            '35 Benutzer',
            '175 GB',
            'bis 50 Benutzer',
        ], false)
        ->assertSee('25 × CHF 44.90')
        ->assertSee("CHF 13'470.00")
        ->assertSee('10 × CHF 7.90')
        ->assertSee('CHF 948.00')
        ->assertSee('Ausgelegt für bis 50 Benutzer')
        ->assertSee("CHF 1'152.00")
        ->assertSee('Speicherplatz 175 GB')
        ->assertSee('CHF 336.00')
        ->assertSee('CHF 144.00')
        ->assertSee('Sie gelten bei jährlicher Abrechnung.');
})->group('odoo-pricing');

it('switches every figure and text to monthly billing', function () {
    get(summaryUrl(['full' => 25, 'light' => 10, 'billing' => 'monthly']))
        ->assertOk()
        ->assertSee('bei monatlicher Abrechnung.')
        ->assertSee('Sie gelten bei monatlicher Abrechnung.')
        ->assertSee('25 × CHF 56.90')
        ->assertSee("CHF 1'671.50")
        ->assertSee("CHF 20'058.00");
})->group('odoo-pricing');

it('leaves out the light users when there are none', function () {
    get(summaryUrl(['full' => 10, 'light' => 0, 'billing' => 'yearly']))
        ->assertOk()
        ->assertSee('Berechnet für 10 Benutzer mit Vollzugriff bei jährlicher Abrechnung.')
        ->assertDontSee('× CHF 7.90');
})->group('odoo-pricing');

it('renders the english texts on the english page', function () {
    get(summaryUrl(locale: 'en-ch'))
        ->assertOk()
        ->assertSee('Your Odoo costs')
        ->assertSee('Calculated for 25 full-access users and 10 light users with yearly billing.')
        ->assertDontSee('Benutzer mit Vollzugriff');
})->group('odoo-pricing');

it('is read only and carries no site navigation, footer or on-premises link', function () {
    get(summaryUrl())
        ->assertOk()
        ->assertDontSee('x-data="odooPricing"', false)
        ->assertDontSee('type="range"', false)
        ->assertDontSee('type="number"', false)
        ->assertDontSee('data-copy-button', false)
        ->assertDontSee('faq-on_premises', false)
        ->assertDontSee('aria-label="Menü"', false)
        ->assertDontSee('<footer', false);
})->group('odoo-pricing');

it('hides the monthly column on small screens', function () {
    $html = (string) get(summaryUrl())->assertOk()->getContent();

    expect(substr_count($html, 'hidden sm:table-cell'))->toBe(9);
})->group('odoo-pricing');

it('links back to the calculator with the same configuration and to the contact page', function () {
    $calculator = route('de-ch.erp.systems.pricing.show', ['full' => 25, 'light' => 10, 'billing' => 'monthly']);

    get(summaryUrl(['full' => 25, 'light' => 10, 'billing' => 'monthly']))
        ->assertOk()
        ->assertSee('href="'.e($calculator).'"', false)
        ->assertSee('aria-label="Konfiguration anpassen"', false)
        ->assertSee('href="'.route('de-ch.contact.index').'"', false);
})->group('odoo-pricing');

it('stays out of search engines but previews the configuration when shared', function () {
    seed(PagesTableSeeder::class);

    $url = summaryUrl();
    $english = summaryUrl(locale: 'en-ch');

    get($url)
        ->assertOk()
        ->assertSee('<title>Deine Odoo-Kosten</title>', false)
        ->assertSee('content="noindex,follow"', false)
        ->assertSee('rel="canonical" href="'.e($url).'"', false)
        ->assertSee('hreflang="en-CH" href="'.e($english).'"', false)
        ->assertSee('property="og:description" content="25 Benutzer mit Vollzugriff und 10 Light-Benutzer: CHF 16&#039;050.00 pro Jahr."', false)
        ->assertSee('images/pages/erp.systems.pricing.show.png', false);
})->group('odoo-pricing');

it('describes itself as a web page in the structured data', function () {
    seed(PagesTableSeeder::class);

    $html = (string) get(summaryUrl())->assertOk()->getContent();

    preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

    $graph = collect(Arr::wrap(data_get(json_decode($matches[1] ?? '', true), '@graph')));

    expect(data_get($graph->firstWhere('@type', 'WebPage'), 'url'))->toBe(summaryUrl())
        ->and($graph->firstWhere('@type', 'FAQPage'))->toBeNull();
})->group('odoo-pricing');

it('is not listed in the sitemap', function () {
    seed(PagesTableSeeder::class);
    Cache::forget('sitemap_xml');

    get('/sitemap.xml')
        ->assertOk()
        ->assertDontSee('zusammenfassung')
        ->assertDontSee('pricing/summary');
})->group('odoo-pricing');
