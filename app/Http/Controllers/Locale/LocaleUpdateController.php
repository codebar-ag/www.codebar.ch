<?php

namespace App\Http\Controllers\Locale;

use App\Actions\LocaleAction;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Spatie\ResponseCache\Commands\ClearCommand;

class LocaleUpdateController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'language' => ['required', new Enum(LocaleEnum::class)],
        ]);

        $locale = (new LocaleAction(Arr::get($validated, 'language')))->setLocale();

        $previousUrl = url()->previous();
        $route = Route::getRoutes()->match($request->create($previousUrl));
        $currentName = $route->getName() ?? '';

        Artisan::call(ClearCommand::class);

        // Only retarget routes registered inside the locale-named groups (en-ch.*, de-ch.*).
        // Routes outside those groups (e.g. previews) stay on the same URL with the new locale in session.
        if (Str::contains($currentName, '.')) {
            $targetName = Str::slug($locale).'.'.Str::after($currentName, '.');
            if (Route::has($targetName)) {
                return redirect()->route($targetName, $route->parameters());
            }
        }

        return redirect()->to($previousUrl);
    }
}
