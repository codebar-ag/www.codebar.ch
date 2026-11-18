<?php

declare(strict_types=1);

use App\Notifications\OdooPricesChangedNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

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
        'www.odoo.sh/pricing' => Http::response('<html><script src="/web/assets/1/abc/other.js"></script><script src="/web/assets/1/abc/web.assets_frontend_lazy.min.js"></script></html>'),
        'www.odoo.sh/web/assets/1/abc/other.js' => Http::response('console.log(1)'),
        'www.odoo.sh/web/assets/1/abc/web.assets_frontend_lazy.min.js' => Http::response($script),
    ]);
}

it('succeeds when the published prices match the config', function () {
    Notification::fake();
    fakeOdooSources();

    runArtisan('odoo:check-prices')->assertSuccessful();

    Notification::assertNothingSent();
})->group('odoo-pricing');

it('fails and notifies when a licence price changed', function () {
    Notification::fake();
    config()->set('odoo-pricing.notify', 'prices@example.com');
    fakeOdooSources(custom: '46,90');

    runArtisan('odoo:check-prices')
        ->expectsOutputToContain('license.full.yearly')
        ->assertFailed();

    Notification::assertSentTo(
        new AnonymousNotifiable,
        OdooPricesChangedNotification::class,
        fn (OdooPricesChangedNotification $notification): bool => str_contains(implode(' ', $notification->findings), 'license.full.yearly: config 4490, published 4690'),
    );
})->group('odoo-pricing');

it('fails when a hosting price changed', function () {
    Notification::fake();
    fakeOdooSources(worker: '65');

    runArtisan('odoo:check-prices')->assertFailed();
})->group('odoo-pricing');

it('fails without sending mail when no recipient is configured', function () {
    Notification::fake();
    config()->set('odoo-pricing.notify', null);
    fakeOdooSources(light: '8,90');

    runArtisan('odoo:check-prices')->assertFailed();

    Notification::assertNothingSent();
})->group('odoo-pricing');

it('reports a source it cannot read instead of staying silent', function () {
    Notification::fake();
    config()->set('odoo-pricing.notify', 'prices@example.com');

    Http::fake([
        'www.odoo.com/*' => Http::response('<html><body>Neue Seite ohne Preise</body></html>'),
        'www.odoo.sh/*' => Http::response('', 503),
    ]);

    runArtisan('odoo:check-prices')->assertFailed();

    Notification::assertSentTo(
        new AnonymousNotifiable,
        OdooPricesChangedNotification::class,
        fn (OdooPricesChangedNotification $notification): bool => count($notification->findings) === 2,
    );
})->group('odoo-pricing');

it('changes nothing in the config', function () {
    Notification::fake();
    fakeOdooSources(custom: '99,90');

    runArtisan('odoo:check-prices')->assertFailed();

    expect(config('odoo-pricing.license.full.yearly'))->toBe(4490);
})->group('odoo-pricing');

it('lists every finding in the notification mail', function () {
    $mail = (new OdooPricesChangedNotification(['license.full.yearly: config 4490, published 4690.']))
        ->toMail(new AnonymousNotifiable);

    expect($mail->subject)->toContain('Odoo-Preise')
        ->and((string) json_encode($mail->introLines))->toContain('license.full.yearly: config 4490, published 4690.')
        ->and($mail->actionUrl)->toBe(route('de-ch.erp.systems.pricing.show'));
})->group('odoo-pricing');
