<?php

declare(strict_types=1);

use App\Actions\LocaleAction;
use App\Enums\SessionKeyEnum;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

it('sets the application locale and stores it in the session', function () {
    $locale = (new LocaleAction('en_CH'))->setLocale();

    expect($locale)->toBe('en_CH');
    expect(App::getLocale())->toBe('en_CH');
    expect(Session::get(SessionKeyEnum::LANGUAGE->value))->toBe('en_CH');
})->group('unit', 'actions');
