<?php

use Illuminate\Support\Facades\Route;

use function Pest\Laravel\get;

beforeEach(function () {
    config(['app.debug' => false]);
    app()->setLocale('en_CH');
});

it('renders custom 404 page', function () {
    get('/nonexistent-route-for-error-page-test-8f3c2a1b')
        ->assertStatus(404)
        ->assertSee(__('errors.title_client'), false)
        ->assertSee('404', false)
        ->assertSee(__('errors.back_home'), false);
});

it('renders custom 500 page', function () {
    Route::get('/__http_error_pages_500', fn () => abort(500));

    get('/__http_error_pages_500')
        ->assertStatus(500)
        ->assertSee(__('errors.title_server'), false)
        ->assertSee('500', false)
        ->assertSee(__('errors.back_home'), false);
});
