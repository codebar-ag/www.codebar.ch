<?php

namespace App\Http\Controllers\Locale;

use App\Actions\LocaleAction;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;

class LocaleUpdateController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(LocaleEnum $locale)
    {
        (new LocaleAction($locale->value))->setLocale();

        return back();
    }
}
