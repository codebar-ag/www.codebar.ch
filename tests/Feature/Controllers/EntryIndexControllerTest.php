<?php

use App\Enums\LocaleEnum;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

it('redirects to the correct localized start page', function () {
    $locale = LocaleEnum::DE->value;
    App::setLocale($locale);

    $response = $this->get('/');

    $expectedRoute = route(Str::slug($locale).'.start.index');

    $response->assertRedirect($expectedRoute);
})->group('entry');
