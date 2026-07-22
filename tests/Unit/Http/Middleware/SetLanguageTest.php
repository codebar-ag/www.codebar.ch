<?php

use App\Enums\LocaleEnum;
use App\Enums\SessionKeyEnum;
use App\Http\Middleware\SetLanguage;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    Route::middleware(['web', SetLanguage::class])->get('/test', function () {
        return 'Middleware Test';
    });
});

it('sets locale based on authenticated user', function () {
    $user = User::factory()->create([
        'locale' => LocaleEnum::DE,
    ]);

    actingAs($user)
        ->get('/test')
        ->assertOk();

    expect(App::getLocale())->toBe(LocaleEnum::DE->value);
})->group('middleware', 'locale');

it('sets locale based on session when user is not authenticated', function () {
    Session::put(SessionKeyEnum::LANGUAGE->value, LocaleEnum::EN->value);

    get('/test')
        ->assertOk();

    expect(App::getLocale())->toBe(LocaleEnum::EN->value);
})->group('middleware', 'locale');

it('defaults to English locale when no user or session language is set', function () {
    get('/test')
        ->assertOk();

    expect(App::getLocale())->toBe(LocaleEnum::DE->value);
})->group('middleware', 'locale');
