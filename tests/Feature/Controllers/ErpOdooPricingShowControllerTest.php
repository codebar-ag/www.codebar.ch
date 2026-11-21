<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use App\Models\Service;
use Database\Seeders\PagesTableSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

it('returns 200 for the pricing page in both locales', function (string $locale) {
    get(route(Str::slug($locale).'.erp.systems.pricing.show'))->assertOk();
})->with([LocaleEnum::DE->value, LocaleEnum::EN->value])->group('odoo-pricing');

it('uses a translated path per locale', function () {
    expect(route('de-ch.erp.systems.pricing.show', [], false))->toBe('/erp/odoo/preise')
        ->and(route('en-ch.erp.systems.pricing.show', [], false))->toBe('/erp/odoo/pricing');
})->group('odoo-pricing');

it('renders the default calculation without javascript', function () {
    get(route('de-ch.erp.systems.pricing.show'))
        ->assertOk()
        ->assertSee('Benutzer mit Vollzugriff')
        ->assertSee('10 × CHF 44.90')
        ->assertSee("CHF 5'388.00")
        ->assertSee('CHF 816.00')
        ->assertSee("CHF 6'204.00")
        ->assertSee('CHF 517.00');
})->group('odoo-pricing');

it('renders the english texts on the english page', function () {
    get(route('en-ch.erp.systems.pricing.show'))
        ->assertOk()
        ->assertSee('What Odoo costs to run')
        ->assertSee('Full-access users')
        ->assertDontSee('Benutzer mit Vollzugriff');
})->group('odoo-pricing');

it('never shows the first year discount or the exchange rate', function () {
    get(route('de-ch.erp.systems.pricing.show'))
        ->assertOk()
        ->assertDontSee('35.90')
        ->assertDontSee('1 EUR')
        ->assertDontSee('Worker');
})->group('odoo-pricing');

it('passes the calculator its configuration without internal values', function () {
    $html = (string) get(route('de-ch.erp.systems.pricing.show'))->assertOk()->getContent();

    preg_match('/data-config="([^"]+)"/', $html, $matches);

    $config = json_decode(html_entity_decode($matches[1] ?? '', ENT_QUOTES), true);
    $keys = is_array($config) ? array_keys($config) : [];

    expect($keys)->toContain('license', 'hosting', 'tiers', 'max_users')
        ->and(array_intersect($keys, ['sources', 'checked_at']))->toBe([]);
})->group('odoo-pricing');

it('links every info icon to an existing faq entry', function () {
    $html = (string) get(route('de-ch.erp.systems.pricing.show'))->assertOk()->getContent();

    expect($html)->toContain('href="#faq-full"')
        ->toContain('href="#faq-light"')
        ->toContain('id="faq-full"')
        ->toContain('id="faq-light"');
})->group('odoo-pricing');

it('links the licence and hosting badges to their odoo pages', function () {
    $html = (string) get(route('de-ch.erp.systems.pricing.show'))->assertOk()->getContent();

    expect($html)->toContain('href="'.config()->string('odoo-pricing.sources.license').'"')
        ->toContain('href="'.config()->string('odoo-pricing.sources.hosting').'"');
})->group('odoo-pricing');

it('mentions on-premises in the hosting block and links to its faq entry', function () {
    $html = (string) get(route('de-ch.erp.systems.pricing.show'))->assertOk()->getContent();

    expect($html)->toContain('Auch On-Premises möglich')
        ->toContain('href="#faq-on_premises"')
        ->toContain('id="faq-on_premises"');
})->group('odoo-pricing');

it('links to the contact page from the closing call to action', function () {
    get(route('de-ch.erp.systems.pricing.show'))
        ->assertOk()
        ->assertSee(route('de-ch.contact.index'), false);
})->group('odoo-pricing');

it('describes the questions as structured data', function () {
    seed(PagesTableSeeder::class);

    $html = (string) get(route('de-ch.erp.systems.pricing.show'))->assertOk()->getContent();

    preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

    $decoded = json_decode($matches[1] ?? '', true);
    $graph = collect(Arr::wrap(data_get($decoded, '@graph')));
    $faq = $graph->firstWhere('@type', 'FAQPage');

    expect(json_last_error())->toBe(JSON_ERROR_NONE)
        ->and(data_get($faq, 'mainEntity'))->toHaveCount(6)
        ->and(data_get($faq, 'mainEntity.0.@type'))->toBe('Question')
        ->and(data_get($faq, 'mainEntity.0.acceptedAnswer.text'))->not->toBeEmpty()
        ->and(data_get($graph->firstWhere('@type', 'WebPage'), 'url'))->toBe(route('de-ch.erp.systems.pricing.show'))
        ->and($graph->firstWhere('@type', 'BreadcrumbList'))->toBeArray();
})->group('odoo-pricing');

it('sets title, canonical, robots and both language alternates', function () {
    seed(PagesTableSeeder::class);

    get(route('de-ch.erp.systems.pricing.show'))
        ->assertOk()
        ->assertSee('<title>Odoo Preise: Lizenzen und Hosting berechnen', false)
        ->assertSee('rel="canonical" href="'.route('de-ch.erp.systems.pricing.show').'"', false)
        ->assertSee('hreflang="en-CH" href="'.route('en-ch.erp.systems.pricing.show').'"', false)
        ->assertSee('content="index,follow"', false);
})->group('odoo-pricing');

it('lists the pricing page in the sitemap in both locales', function () {
    seed(PagesTableSeeder::class);

    Cache::flush();

    get('/sitemap.xml')
        ->assertOk()
        ->assertSee(route('de-ch.erp.systems.pricing.show'), false)
        ->assertSee(route('en-ch.erp.systems.pricing.show'), false);
})->group('odoo-pricing');

it('redirects the bare erp paths to the pricing page', function (string $path) {
    get($path)->assertRedirect(route('de-ch.erp.systems.pricing.show'));
})->with(['/erp', '/erp/odoo'])->group('odoo-pricing');

it('shows the pricing button only on the open source erp service', function () {
    Service::factory()->create(['slug' => 'open-source-erp', 'name' => 'Open Source ERP', 'published' => true]);

    get(route('de-ch.services.index'))
        ->assertOk()
        ->assertSee(route('de-ch.erp.systems.pricing.show'), false)
        ->assertSee('Odoo Preise');
})->group('odoo-pricing');

it('shows no pricing button without the open source erp service', function () {
    Service::factory()->create(['slug' => 'dms-ecm-consulting', 'published' => true]);

    get(route('de-ch.services.index'))
        ->assertOk()
        ->assertDontSee(route('de-ch.erp.systems.pricing.show'), false);
})->group('odoo-pricing');

it('keeps both languages complete', function () {
    $flatten = fn (string $locale): array => array_keys(Arr::dot(trans('components.odoo_pricing', [], $locale)));

    expect($flatten('en_CH'))->toBe($flatten('de_CH'));
})->group('odoo-pricing');

it('keeps the plain address when no configuration is asked for', function () {
    get(route('de-ch.erp.systems.pricing.show', ['utm_source' => 'mail']))
        ->assertOk()
        ->assertSee('data-selection="{&quot;full&quot;:10,&quot;light&quot;:0,&quot;billing&quot;:&quot;yearly&quot;}"', false);
})->group('odoo-pricing');

it('starts the calculator from the configuration in the address', function () {
    get(route('de-ch.erp.systems.pricing.show', ['full' => 25, 'light' => 10, 'billing' => 'monthly']))
        ->assertOk()
        ->assertSee('data-selection="{&quot;full&quot;:25,&quot;light&quot;:10,&quot;billing&quot;:&quot;monthly&quot;}"', false)
        ->assertSee('id="odoo-full" inputmode="numeric" data-kind="full"', false)
        ->assertSee('value="25"', false)
        ->assertSee('value="10"', false)
        ->assertSee('x-bind:aria-pressed="isMonthly" aria-pressed="true"', false)
        ->assertSee('x-bind:aria-pressed="isYearly" aria-pressed="false"', false)
        ->assertSee("CHF 20'058.00")
        ->assertSee('Sie gelten bei monatlicher Abrechnung.');
})->group('odoo-pricing');

it('redirects an invalid configuration in the address to its normalised form', function (array $query, array $expected) {
    get(route('de-ch.erp.systems.pricing.show', $query))
        ->assertRedirect(route('de-ch.erp.systems.pricing.show', $expected));
})->with([
    'partial' => [['light' => 4], ['light' => 4, 'full' => 10, 'billing' => 'yearly']],
    'text' => [['full' => 'abc', 'light' => 0, 'billing' => 'yearly'], ['full' => 10, 'light' => 0, 'billing' => 'yearly']],
    'above maximum' => [['full' => 151, 'light' => 0, 'billing' => 'yearly'], ['full' => 150, 'light' => 0, 'billing' => 'yearly']],
    'unknown billing' => [['full' => 5, 'light' => 0, 'billing' => 'daily'], ['full' => 5, 'light' => 0, 'billing' => 'yearly']],
])->group('odoo-pricing');

it('offers to share the configuration as a summary link', function () {
    get(route('de-ch.erp.systems.pricing.show'))
        ->assertOk()
        ->assertSee('data-share-button', false)
        ->assertSee('data-summary-url="'.route('de-ch.erp.systems.pricing.summary').'"', false)
        ->assertSee('data-share-title="Deine Odoo-Kosten"', false)
        ->assertSee('data-share-copied="Link kopiert"', false)
        ->assertSee('Teilen');
})->group('odoo-pricing');

it('hides the monthly column on small screens without hiding the light row', function () {
    $html = (string) get(route('de-ch.erp.systems.pricing.show'))->assertOk()->getContent();

    expect(substr_count($html, 'hidden sm:table-cell'))->toBe(9)
        ->and($html)->toContain('x-ref="lightRow" class="invisible"');
})->group('odoo-pricing');

it('paints the cost split without a generated width class', function () {
    get(route('de-ch.erp.systems.pricing.show'))
        ->assertOk()
        ->assertSee('style="width: 87%"', false)
        ->assertSee('style="width: 13%"', false)
        ->assertDontSee('w-[87%]', false);
})->group('odoo-pricing');
