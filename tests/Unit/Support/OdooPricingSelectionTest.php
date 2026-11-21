<?php

declare(strict_types=1);

use App\Support\OdooPricing;
use App\Support\OdooPricingSelection;

it('starts from the configured defaults', function () {
    expect(OdooPricingSelection::defaults()->query())->toBe(['full' => 10, 'light' => 0, 'billing' => 'yearly']);
})->group('odoo-pricing');

it('reads valid values from the query', function () {
    $selection = OdooPricingSelection::fromQuery(['full' => '25', 'light' => '10', 'billing' => 'monthly']);

    expect($selection->query())->toBe(['full' => 25, 'light' => 10, 'billing' => 'monthly']);
})->group('odoo-pricing');

it('falls back to the default for anything that is not a whole number', function (mixed $value) {
    expect(OdooPricingSelection::fromQuery(['full' => $value, 'light' => $value])->query())
        ->toBe(['full' => 10, 'light' => 0, 'billing' => 'yearly']);
})->with([
    'empty' => [''],
    'text' => ['abc'],
    'negative' => ['-5'],
    'decimal' => ['1.5'],
    'exponent' => ['1e2'],
    'hex' => ['0x10'],
    'leading space' => [' 5'],
    'trailing space' => ['5 '],
    'too many digits' => ['123456'],
    'array' => [['5']],
    'null' => [null],
    'integer type' => [5],
])->group('odoo-pricing');

it('reads leading zeros as a number', function () {
    expect(OdooPricingSelection::fromQuery(['full' => '0025'])->full)->toBe(25);
})->group('odoo-pricing');

it('caps every count at the maximum number of users', function () {
    expect(OdooPricingSelection::fromQuery(['full' => '9999', 'light' => '0'])->full)->toBe(150)
        ->and(OdooPricingSelection::fromQuery(['full' => '0', 'light' => '9999'])->light)->toBe(150);
})->group('odoo-pricing');

it('gives the light users only the room the full users leave', function () {
    expect(OdooPricingSelection::fromQuery(['full' => '100', 'light' => '100'])->query())
        ->toBe(['full' => 100, 'light' => 50, 'billing' => 'yearly']);
})->group('odoo-pricing');

it('never drops below one user in total', function () {
    expect(OdooPricingSelection::fromQuery(['full' => '0', 'light' => '0'])->query())
        ->toBe(['full' => 1, 'light' => 0, 'billing' => 'yearly']);
})->group('odoo-pricing');

it('accepts only the known billing periods', function (mixed $billing, string $expected) {
    expect(OdooPricingSelection::fromQuery(['billing' => $billing])->billing)->toBe($expected);
})->with([
    'yearly' => ['yearly', 'yearly'],
    'monthly' => ['monthly', 'monthly'],
    'upper case' => ['MONTHLY', 'yearly'],
    'unknown' => ['weekly', 'yearly'],
    'empty' => ['', 'yearly'],
    'array' => [['monthly'], 'yearly'],
])->group('odoo-pricing');

it('knows whether the query asks for a configuration at all', function (array $query, bool $expected) {
    expect(OdooPricingSelection::isRequested($query))->toBe($expected);
})->with([
    'nothing' => [[], false],
    'tracking only' => [['utm_source' => 'mail'], false],
    'one key' => [['light' => '3'], true],
    'all keys' => [['full' => '1', 'light' => '2', 'billing' => 'yearly'], true],
])->group('odoo-pricing');

it('matches only the exact normalised query', function (array $query, bool $expected) {
    $selection = OdooPricingSelection::fromQuery(['full' => '25', 'light' => '10', 'billing' => 'yearly']);

    expect($selection->matches($query))->toBe($expected);
})->with([
    'exact' => [['full' => '25', 'light' => '10', 'billing' => 'yearly'], true],
    'with tracking' => [['full' => '25', 'light' => '10', 'billing' => 'yearly', 'utm_source' => 'mail'], true],
    'leading zero' => [['full' => '025', 'light' => '10', 'billing' => 'yearly'], false],
    'missing billing' => [['full' => '25', 'light' => '10'], false],
    'integers' => [['full' => 25, 'light' => 10, 'billing' => 'yearly'], false],
    'empty' => [[], false],
])->group('odoo-pricing');

it('calculates the same result as the calculator', function () {
    $selection = OdooPricingSelection::fromQuery(['full' => '25', 'light' => '10', 'billing' => 'monthly']);

    expect($selection->result())->toBe(OdooPricing::calculate(25, 10, 'monthly'));
})->group('odoo-pricing');

it('names the users in a headline', function (string $locale, int $full, int $light, string $expected) {
    app()->setLocale($locale);

    expect(OdooPricing::headline($full, $light))->toBe($expected);
})->with([
    'de both' => ['de_CH', 25, 10, '25 Benutzer mit Vollzugriff und 10 Light-Benutzer'],
    'de one each' => ['de_CH', 1, 1, '1 Benutzer mit Vollzugriff und 1 Light-Benutzer'],
    'de full only' => ['de_CH', 10, 0, '10 Benutzer mit Vollzugriff'],
    'de light only' => ['de_CH', 0, 4, '4 Light-Benutzer'],
    'en both' => ['en_CH', 25, 10, '25 full-access users and 10 light users'],
    'en one each' => ['en_CH', 1, 1, '1 full-access user and 1 light user'],
    'en light only' => ['en_CH', 0, 1, '1 light user'],
])->group('odoo-pricing');
