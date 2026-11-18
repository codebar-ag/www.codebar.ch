<?php

declare(strict_types=1);

use App\Models\OdooPriceSnapshot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Spatie\ResponseCache\Facades\ResponseCache;

use function Pest\Laravel\get;
use function Pest\Laravel\travelTo;

function fakeOdooSources(string $custom = '44,90', string $monthly = '56,90', string $light = '7,90', string $worker = '60'): void
{
    $license = <<<HTML
        <html><body>
        <h3>Standard</h3><span>€</span><span>19</span><span>24</span><span>,90</span><span>,90</span><s>24,90 €</s><b>31,10 €</b>
        <h3>Custom</h3><span>€</span><span>35</span><span>44</span><span>,90</span><span>,90</span><s>{$custom} €</s><b>{$monthly} €</b>
        <p>Light-Benutzer zu <b>{$light}</b> €</p>
        </body></html>
        HTML;

    $script = "this.prices={'€':{'workers':{$worker},'hosting':500,'staging':15,'storage':0.20,},'$':{'workers':72,},};this.periodicity_factor={'yearly':0.8,'monthly':1,}";

    Http::fake([
        'www.odoo.com/*' => Http::response($license),
        'www.odoo.sh/pricing' => Http::response('<html><script src="/web/assets/1/abc/other.js"></script><script src="https://www.odoo.sh/web/assets/1/abc/web.assets_frontend_lazy.min.js"></script></html>'),
        'www.odoo.sh/web/assets/1/abc/other.js' => Http::response('console.log(1)'),
        'www.odoo.sh/web/assets/1/abc/web.assets_frontend_lazy.min.js' => Http::response($script),
    ]);
}

it('stores the published prices on the first run', function () {
    fakeOdooSources();

    runArtisan('odoo:sync-prices')->assertSuccessful();

    expect(OdooPriceSnapshot::query()->count())->toBe(1)
        ->and(OdooPriceSnapshot::query()->firstOrFail()->prices)->toBe(OdooPriceSnapshot::configured());
})->group('odoo-pricing');

it('keeps one snapshot while the prices stay the same', function () {
    OdooPriceSnapshot::query()->create(['prices' => OdooPriceSnapshot::configured()]);
    ResponseCache::shouldReceive('clear')->never();
    fakeOdooSources();

    runArtisan('odoo:sync-prices')->expectsOutputToContain('unchanged')->assertSuccessful();

    expect(OdooPriceSnapshot::query()->count())->toBe(1);
})->group('odoo-pricing');

it('takes over a changed price and clears the rendered pages', function () {
    ResponseCache::shouldReceive('clear')->once();
    fakeOdooSources(custom: '46,90', worker: '65');

    runArtisan('odoo:sync-prices')->expectsOutputToContain('license.full.yearly')->assertSuccessful();

    $prices = OdooPriceSnapshot::query()->firstOrFail()->prices;

    expect($prices['license.full.yearly'])->toBe(4690)
        ->and($prices['hosting.worker'])->toBe(6500);
})->group('odoo-pricing');

it('shows the stored prices on the pricing page', function () {
    fakeOdooSources(custom: '46,90', light: '8,90');
    runArtisan('odoo:sync-prices')->assertSuccessful();

    get(route('de-ch.erp.systems.pricing.show'))
        ->assertOk()
        ->assertSee('10 × CHF 46.90')
        ->assertSee('CHF 8.90')
        ->assertDontSee('CHF 44.90');
})->group('odoo-pricing');

it('shows the configured prices while nothing is stored', function () {
    get(route('de-ch.erp.systems.pricing.show'))
        ->assertOk()
        ->assertSee('10 × CHF 44.90');

    expect(OdooPriceSnapshot::apply())->toBeNull();
})->group('odoo-pricing');

it('refuses an implausible price and keeps the last valid one', function () {
    OdooPriceSnapshot::query()->create(['prices' => OdooPriceSnapshot::configured()]);
    ResponseCache::shouldReceive('clear')->never();
    fakeOdooSources(custom: '449,00');

    runArtisan('odoo:sync-prices')
        ->expectsOutputToContain('outside the plausible range')
        ->expectsOutputToContain('last valid Odoo prices stay in use')
        ->assertSuccessful();

    expect(OdooPriceSnapshot::query()->count())->toBe(1)
        ->and(OdooPriceSnapshot::query()->firstOrFail()->prices['license.full.yearly'])->toBe(4490);
})->group('odoo-pricing');

it('changes nothing when a source cannot be read', function () {
    Http::fake([
        'www.odoo.com/*' => Http::response('<html><body>Neue Seite ohne Preise</body></html>'),
        'www.odoo.sh/*' => Http::response('', 503),
    ]);

    runArtisan('odoo:sync-prices')->assertSuccessful();

    expect(OdooPriceSnapshot::query()->count())->toBe(0);
})->group('odoo-pricing');

it('changes nothing when the hosting page answers with an error', function () {
    Http::fake([
        'www.odoo.com/*' => Http::response('<h3>Custom</h3> 44,90 € 56,90 € Light 7,90'),
        'www.odoo.sh/*' => Http::response('', 503),
    ]);

    runArtisan('odoo:sync-prices')->expectsOutputToContain('503')->assertSuccessful();

    expect(OdooPriceSnapshot::query()->count())->toBe(0);
})->group('odoo-pricing');

it('changes nothing when the hosting scripts carry no price table', function () {
    Http::fake([
        'www.odoo.com/*' => Http::response('<h3>Custom</h3> 44,90 € 56,90 € Light 7,90'),
        'www.odoo.sh/pricing' => Http::response('<script src="/a.js"></script>'),
        'www.odoo.sh/a.js' => Http::response("this.prices={'€':{'workers':60,'hosting':500,'staging':15,'storage':0.20,}}"),
    ]);

    runArtisan('odoo:sync-prices')->expectsOutputToContain('price table')->assertSuccessful();

    expect(OdooPriceSnapshot::query()->count())->toBe(0);
})->group('odoo-pricing');

it('uses the date of the last successful check on the page', function () {
    fakeOdooSources();
    travelTo(Carbon::parse('2027-02-01 07:00:00'));
    runArtisan('odoo:sync-prices')->assertSuccessful();

    get(route('de-ch.erp.systems.pricing.show'))->assertOk()->assertSee('01.02.2027');
})->group('odoo-pricing');

it('ignores a stored value that is not a number', function () {
    OdooPriceSnapshot::query()->create(['prices' => ['license.full.yearly' => 'viel', 'hosting.worker' => 6500]]);

    OdooPriceSnapshot::apply();

    expect(config('odoo-pricing.license.full.yearly'))->toBe(4490)
        ->and(config('odoo-pricing.hosting.worker'))->toBe(6500);
})->group('odoo-pricing');
