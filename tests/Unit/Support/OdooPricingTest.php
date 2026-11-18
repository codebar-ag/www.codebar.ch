<?php

declare(strict_types=1);

use App\Support\OdooPricing;
use Illuminate\Support\Arr;

it('calculates licences, hosting and the total per year', function (int $full, int $light, string $billing, int $license, int $hosting, int $total) {
    $result = OdooPricing::calculate($full, $light, $billing);

    expect($result['license_month'] * 12)->toBe($license)
        ->and($result['hosting_month'] * 12)->toBe($hosting)
        ->and($result['total_month'] * 12)->toBe($total);
})->with([
    'ten full users, yearly' => [10, 0, 'yearly', 538800, 81600, 620400],
    'ten full users, monthly' => [10, 0, 'monthly', 682800, 102000, 784800],
    'full and light users' => [15, 12, 'yearly', 921960, 155520, 1077480],
    'one user' => [1, 0, 'yearly', 53880, 72960, 126840],
    'upper limit' => [150, 0, 'yearly', 8082000, 458304, 8540304],
])->group('unit', 'odoo-pricing');

it('picks the hosting tier from all users together', function (int $full, int $light, int $tier, int $workers) {
    $result = OdooPricing::calculate($full, $light);

    expect($result['tier_up_to'])->toBe($tier)
        ->and($result['workers'])->toBe($workers);
})->with([
    [25, 0, 25, 1],
    [26, 0, 50, 2],
    [20, 6, 50, 2],
    [100, 0, 100, 4],
    [101, 0, 125, 5],
    [126, 24, 150, 6],
])->group('unit', 'odoo-pricing');

it('keeps the user counts inside the limits', function (int $full, int $light, int $expectedFull, int $expectedLight) {
    $result = OdooPricing::calculate($full, $light);

    expect($result['full_users'])->toBe($expectedFull)
        ->and($result['light_users'])->toBe($expectedLight)
        ->and($result['users'])->toBeLessThanOrEqual(config()->integer('odoo-pricing.max_users'));
})->with([
    'zero full users' => [0, 0, 1, 0],
    'negative values' => [-5, -3, 1, 0],
    'too many full users' => [400, 0, 150, 0],
    'light users fill the rest' => [100, 90, 100, 50],
    'no room for light users' => [150, 10, 150, 0],
    'only light users' => [0, 150, 0, 150],
    'light users with no full user' => [0, 20, 0, 20],
])->group('unit', 'odoo-pricing');

it('charges five gigabytes per user and stops at the shared hosting limit', function () {
    expect(OdooPricing::calculate(10, 0)['storage_gb'])->toBe(50)
        ->and(OdooPricing::calculate(10, 0)['storage_capped'])->toBe(0)
        ->and(OdooPricing::calculate(102, 0)['storage_gb'])->toBe(510)
        ->and(OdooPricing::calculate(103, 0)['storage_gb'])->toBe(512)
        ->and(OdooPricing::calculate(103, 0)['storage_capped'])->toBe(1)
        ->and(OdooPricing::calculate(150, 0)['storage_gb'])->toBe(512);
})->group('unit', 'odoo-pricing');

it('always includes exactly one test system', function () {
    $yearly = OdooPricing::calculate(10, 0);
    $monthly = OdooPricing::calculate(10, 0, 'monthly');

    expect($yearly['staging_month'])->toBe(1200)
        ->and($monthly['staging_month'])->toBe(1500);
})->group('unit', 'odoo-pricing');

it('falls back to yearly billing for an unknown period', function () {
    expect(OdooPricing::calculate(10, 0, 'weekly'))->toBe(OdooPricing::calculate(10, 0, 'yearly'));
})->group('unit', 'odoo-pricing');

it('applies the exchange rate to every price', function () {
    config()->set('odoo-pricing.eur_to_chf', 0.95);

    $result = OdooPricing::calculate(10, 0);

    expect($result['full_unit'])->toBe(4266)
        ->and($result['worker_unit'])->toBe(4560);
})->group('unit', 'odoo-pricing');

it('formats amounts as Swiss francs', function (int $cents, string $expected) {
    expect(OdooPricing::format($cents))->toBe($expected);
})->with([
    [0, 'CHF 0.00'],
    [790, 'CHF 7.90'],
    [620400, "CHF 6'204.00"],
    [8540304, "CHF 85'403.04"],
])->group('unit', 'odoo-pricing');

it('hands the calculator only the values it needs', function () {
    $keys = array_keys(OdooPricing::calculatorConfig());

    expect($keys)->toContain('license', 'hosting', 'tiers', 'max_users', 'storage_gb_per_user', 'storage_gb_max')
        ->and(array_intersect($keys, ['sources', 'checked_at']))->toBe([]);
})->group('unit', 'odoo-pricing');

it('matches the shared fixture the javascript calculator is tested against', function () {
    $fixture = json_decode((string) file_get_contents(base_path('tests/Fixtures/odoo-pricing.json')), true, flags: JSON_THROW_ON_ERROR);

    expect(data_get($fixture, 'config'))->toEqual(OdooPricing::calculatorConfig());

    foreach (Arr::wrap(data_get($fixture, 'cases')) as $case) {
        $full = data_get($case, 'full');
        $light = data_get($case, 'light');
        $billing = data_get($case, 'billing');

        if (! is_int($full) || ! is_int($light) || ! is_string($billing)) {
            throw new RuntimeException('Malformed case in tests/Fixtures/odoo-pricing.json.');
        }

        $result = OdooPricing::calculate($full, $light, $billing);

        expect($result['total_month'])->toBe(data_get($case, 'expected.totalMonth'))
            ->and($result['hosting_month'])->toBe(data_get($case, 'expected.hostingMonth'))
            ->and($result['license_month'])->toBe(data_get($case, 'expected.licenseMonth'));
    }
})->group('unit', 'odoo-pricing');
