<?php

use App\Enums\LocaleEnum;
use App\Enums\SessionKeyEnum;
use App\Http\Middleware\SetLanguage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

beforeEach(function () {
    Route::middleware(['web', SetLanguage::class])->get('/test', fn () => 'Middleware Test');
});

it('sets locale from session when present', function () {
    Session::put(SessionKeyEnum::LANGUAGE->value, LocaleEnum::EN->value);

    $this->get('/test')->assertOk();

    expect(App::getLocale())->toBe(LocaleEnum::EN->value);
});

it('defaults to German when no session locale is set', function () {
    $this->get('/test')->assertOk();

    expect(App::getLocale())->toBe(LocaleEnum::DE->value);
});
