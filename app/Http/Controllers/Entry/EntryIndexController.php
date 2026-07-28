<?php

namespace App\Http\Controllers\Entry;

use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class EntryIndexController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        // Always the default locale, never the session's: a redirect whose
        // target changes per visitor is one crawlers cannot rely on, and the
        // domain root is the URL most external links point at. It matches the
        // x-default hreflang, which also points at the German start page.
        $locale = Str::slug(LocaleEnum::DE->value);

        return redirect()->route("{$locale}.start.index", status: 301);
    }
}
