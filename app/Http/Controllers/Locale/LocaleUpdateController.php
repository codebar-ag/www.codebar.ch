<?php

namespace App\Http\Controllers\Locale;

use App\Actions\LocaleAction;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Spatie\ResponseCache\Commands\ClearCommand;

class LocaleUpdateController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'language' => ['required', new Enum(LocaleEnum::class)],
        ]);

        if (! is_array($validated) || ! is_string($validated['language'] ?? null)) {
            abort(422);
        }

        $language = $validated['language'];

        $locale = (new LocaleAction($language))->setLocale();

        $previousUrl = url()->previous();

        $route = Route::getRoutes()->match(request()->create($previousUrl));
        $routeName = Str::after((string) $route->getName(), '.');
        $routeParameters = $route->parameters();

        $localeSlug = Str::slug($locale);

        Artisan::call(ClearCommand::class);

        return redirect()->route("{$localeSlug}.{$routeName}", $routeParameters);
    }
}
