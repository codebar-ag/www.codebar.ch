<?php

use App\Enums\LocaleEnum;
use App\Models\Network;
use App\Models\NetworkUser;

it('returns the company rows for all locales', function () {
    Network::factory()->create(['key' => 'docuware', 'locale' => LocaleEnum::DE->value]);
    Network::factory()->create(['key' => 'docuware', 'locale' => LocaleEnum::EN->value]);

    $networkUser = NetworkUser::factory()->create(['network_key' => 'docuware']);

    expect($networkUser->networks)->toHaveCount(2);
})->group('network', 'network-user-model');

it('returns the company row for a given locale', function () {
    Network::factory()->create(['key' => 'docuware', 'locale' => LocaleEnum::DE->value, 'name' => 'DocuWare DE']);
    Network::factory()->create(['key' => 'docuware', 'locale' => LocaleEnum::EN->value, 'name' => 'DocuWare EN']);

    $networkUser = NetworkUser::factory()->create(['network_key' => 'docuware']);

    expect($networkUser->network(LocaleEnum::DE->value)?->name)->toBe('DocuWare DE')
        ->and($networkUser->network(LocaleEnum::EN->value)?->name)->toBe('DocuWare EN');
})->group('network', 'network-user-model');

it('filters by the published scope', function () {
    NetworkUser::factory()->create(['name' => 'Visible', 'published' => true]);
    NetworkUser::factory()->create(['name' => 'Hidden', 'published' => false]);

    expect(NetworkUser::published()->pluck('name')->all())->toBe(['Visible']);
})->group('network', 'network-user-model');

it('builds initials from the name', function () {
    expect(NetworkUser::factory()->make(['name' => 'Dario Wieland'])->initials())->toBe('DW')
        ->and(NetworkUser::factory()->make(['name' => 'Domenik'])->initials())->toBe('D')
        ->and(NetworkUser::factory()->make(['name' => 'Sarah Anna Fässler'])->initials())->toBe('SA');
})->group('network', 'network-user-model');
