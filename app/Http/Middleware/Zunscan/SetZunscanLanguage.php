<?php

declare(strict_types=1);

namespace App\Http\Middleware\Zunscan;

use App\Enums\LocaleEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SetZunscanLanguage
{
    /**
     * The URL is the single source of truth for the language here too (see the
     * main site's SetLanguage), but Zunscan is a single-language client site
     * with no session-backed preference — every route name already carries its
     * locale ("zunscan.de-ch." / "zunscan.en-ch."), so there is nothing to fall
     * back to and nothing worth persisting.
     *
     * Also un-poisons every absolute URL for the rest of this request:
     * AppServiceProvider calls URL::forceRootUrl(config('app.url')) for the
     * main site's own sake, but that forces @vite()'s asset() calls (and any
     * route(..., absolute: true)) to the main site's host too — which is how
     * Zunscan's own CSS/JS ended up being requested from web.codebar.test
     * instead of its own domain. Safe to override here: this only ever runs
     * for Zunscan requests, and PHP-FPM starts each request fresh.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = (string) $request->route()?->getName();

        foreach (LocaleEnum::cases() as $locale) {
            if (Str::startsWith($routeName, 'zunscan.'.Str::slug($locale->value).'.')) {
                app()->setLocale($locale->value);
                break;
            }
        }

        URL::forceRootUrl($request->getSchemeAndHttpHost());

        return $next($request);
    }
}
