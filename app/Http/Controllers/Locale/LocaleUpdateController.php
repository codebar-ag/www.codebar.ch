<?php

namespace App\Http\Controllers\Locale;

use App\Actions\LocaleAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class LocaleUpdateController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(string $locale)
    {
        $locale = (new LocaleAction($locale))->setLocale();

        $previousUrl = url()->previous();

        $previousRoute = app('router')->getRoutes()->match(request()->create($previousUrl));
        $previousRouteName = Str::after($previousRoute?->getName() ?? '', '.');

        $routeParameters = $previousRoute?->parameters();

        $localeSlug = Str::slug($locale);

        return redirect()->route($localeSlug.'.'.$previousRouteName, $routeParameters);
    }
}
