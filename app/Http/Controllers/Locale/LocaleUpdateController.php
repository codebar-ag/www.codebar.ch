<?php

namespace App\Http\Controllers\Locale;

use App\Actions\LocaleAction;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

class LocaleUpdateController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'language' => ['required', new Enum(LocaleEnum::class)],
        ]);

        $locale = Arr::get($validated, 'language');

        $locale = (new LocaleAction($locale))->setLocale();

        $previousUrl = url()->previous();

        $route = Route::getRoutes()->match(request()->create($previousUrl));
        $routeName = Str::after($route?->getName() ?? '', '.');
        $routeParameters = $route?->parameters();

        $localeSlug = Str::slug($locale);

        return redirect()->route("{$localeSlug}.{$routeName}", $routeParameters);
    }
}
