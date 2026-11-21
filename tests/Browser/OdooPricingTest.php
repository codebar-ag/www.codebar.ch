<?php

declare(strict_types=1);

it('writes the configuration into the address while the calculator is used', function () {
    visit('/erp/odoo/preise')
        ->assertQueryStringMissing('full')
        ->click('[aria-label="Einen Benutzer mit Vollzugriff mehr"]')
        ->click('Monatlich')
        ->assertQueryStringHas('full', '11')
        ->assertQueryStringHas('light', '0')
        ->assertQueryStringHas('billing', 'monthly')
        ->assertNoJavaScriptErrors();
})->group('odoo-pricing');

it('opens the calculator with the configuration from a shared address', function () {
    visit('/erp/odoo/preise?full=25&light=10&billing=monthly')
        ->assertValue('#odoo-full', '25')
        ->assertValue('#odoo-light', '10')
        ->assertAttribute('[x-bind\\:aria-pressed="isMonthly"]', 'aria-pressed', 'true')
        ->assertSee("CHF 20'058.00")
        ->assertNoJavaScriptErrors();
})->group('odoo-pricing');

it('corrects an invalid address before the calculator starts', function () {
    visit('/erp/odoo/preise?full=999&light=abc')
        ->assertQueryStringHas('full', '150')
        ->assertQueryStringHas('light', '0')
        ->assertQueryStringHas('billing', 'yearly')
        ->assertValue('#odoo-full', '150')
        ->assertNoJavaScriptErrors();
})->group('odoo-pricing');

it('shares the summary link through the share sheet', function () {
    $page = visit('/erp/odoo/preise?full=25&light=10&billing=yearly');

    $page->script('() => { window.shared = null; navigator.share = (data) => { window.shared = data; return Promise.resolve() } }');

    $page->click('[data-share-button]')
        ->assertScript('window.shared.url', route('de-ch.erp.systems.pricing.summary', ['full' => 25, 'light' => 10, 'billing' => 'yearly']))
        ->assertScript('window.shared.title', 'Deine Odoo-Kosten')
        ->assertNoJavaScriptErrors();
})->group('odoo-pricing');

it('copies the summary link where there is no share sheet', function () {
    $page = visit('/erp/odoo/preise?full=3&light=2&billing=monthly');

    $page->script('() => {
        window.copied = null;
        Object.defineProperty(navigator, "share", { value: undefined, configurable: true });
        Object.defineProperty(navigator, "clipboard", { value: { writeText: (text) => { window.copied = text; return Promise.resolve() } }, configurable: true });
    }');

    $page->click('[data-share-button]')
        ->assertSeeIn('[data-share-button]', 'Link kopiert')
        ->assertScript('window.copied', route('de-ch.erp.systems.pricing.summary', ['full' => 3, 'light' => 2, 'billing' => 'monthly']))
        ->assertNoJavaScriptErrors();
})->group('odoo-pricing');

it('shows the summary read only and goes back to the calculator with the same values', function () {
    visit('/erp/odoo/preise/zusammenfassung?full=25&light=10&billing=yearly')
        ->assertSeeIn('h1:visible', 'Deine Odoo-Kosten')
        ->assertSee("CHF 16'050.00")
        ->assertNoJavaScriptErrors()
        ->assertNoConsoleLogs()
        ->click('[data-adjust]')
        ->assertPathIs('/erp/odoo/preise')
        ->assertQueryStringHas('full', '25')
        ->assertValue('#odoo-full', '25')
        ->assertValue('#odoo-light', '10');
})->group('odoo-pricing');

it('fits a phone screen without sideways scrolling', function (string $path) {
    visit($path)
        ->resize(375, 812)
        ->assertScript('document.documentElement.scrollWidth <= window.innerWidth')
        ->assertNoJavaScriptErrors();
})->with([
    'summary' => ['/erp/odoo/preise/zusammenfassung?full=150&light=0&billing=monthly'],
    'calculator' => ['/erp/odoo/preise?full=150&light=0&billing=monthly'],
])->group('odoo-pricing');

it('shows only the yearly amounts on a phone', function () {
    visit('/erp/odoo/preise/zusammenfassung?full=25&light=10&billing=yearly')
        ->resize(375, 812)
        ->assertScript('getComputedStyle(document.querySelector("th.sm\\\\:table-cell")).display', 'none')
        ->resize(1280, 900)
        ->assertScript('getComputedStyle(document.querySelector("th.sm\\\\:table-cell")).display', 'table-cell');
})->group('odoo-pricing');
