<?php

namespace App\Http\Controllers\Locale;

use App\Enums\LocaleEnum;
use App\Enums\SessionKeyEnum;
use App\Http\Controllers\Controller;

class LocaleUpdateController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(LocaleEnum $locale)
    {
        session()->put(SessionKeyEnum::LANGUAGE->value, $locale->value);
        app()->setLocale($locale->value);

        return back();
    }
}
