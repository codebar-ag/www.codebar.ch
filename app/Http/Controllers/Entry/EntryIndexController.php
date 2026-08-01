<?php

declare(strict_types=1);

namespace App\Http\Controllers\Entry;

use App\Enums\CookieNameEnum;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class EntryIndexController extends Controller
{
    private const int HINT_LIFETIME_IN_MINUTES = 5;

    public function __invoke(): RedirectResponse
    {
        // Always the default locale, never the session's: a redirect whose
        // target changes per visitor is one crawlers cannot rely on, and the
        // domain root is the URL most external links point at. It matches the
        // x-default hreflang, which also points at the German start page.
        $locale = Str::slug(LocaleEnum::DE->value);

        // Marks the arrival as one through the domain root, which is the only
        // arrival that was never a language choice. Attached to the response
        // rather than queued, so it survives the response cache replaying
        // this redirect.
        // A 301 is cached by the browser for good, and a redirect replayed from
        // that cache never reaches us — so without this the marker below is
        // handed out once per browser, ever. The permanence a crawler reads
        // lives in the status code, not in this header.
        return redirect()
            ->route("{$locale}.start.index", status: 301)
            ->header('Cache-Control', 'no-store, private')
            ->cookie(
                CookieNameEnum::ENTRY_REDIRECT->value,
                '1',
                self::HINT_LIFETIME_IN_MINUTES,
                '/',
                null,
                null,
                false,
                false,
                'lax',
            );
    }
}
