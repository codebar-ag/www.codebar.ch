<?php

declare(strict_types=1);

use App\Support\LocalizedRouteParameters;
use Illuminate\Support\Str;

if (! function_exists('localized_route')) {
    function localized_route(string $name, mixed $parameters = [], bool $absolute = true, ?string $override = null): string
    {
        $locale = Str::slug(app()->getLocale());
        $localizedName = Str::slug($override ?? $locale).'.'.$name;

        return route($localizedName, $parameters, $absolute);
    }
}

if (! function_exists('locale_switch_url')) {
    /**
     * The current page's URL in another locale.
     *
     * Lets the language switcher be a real <a href> instead of a POST form.
     * Crawlers cannot submit forms, so with a form the two language versions
     * are only ever connected by the hreflang tags in <head>; a link makes the
     * relationship explicit and lets link equity flow between them.
     *
     * Falls back to that locale's start page when the current route has no
     * counterpart (unnamed routes, error pages).
     */
    function locale_switch_url(string $locale): string
    {
        $localeSlug = Str::slug($locale);
        $route = request()->route();
        $routeName = (string) $route?->getName();

        if ($routeName === '') {
            return route($localeSlug.'.start.index');
        }

        // Route names are locale-prefixed ("de-ch.services.index").
        $routeKey = Str::after($routeName, '.');

        /** @var array<string, mixed> $parameters */
        $parameters = $route?->parameters() ?? [];

        // Detail routes carry the locale in the path as well as the prefix, and their
        // slugs are translated — both have to be swapped together.
        $parameters = LocalizedRouteParameters::for($parameters, $locale);

        try {
            return route($localeSlug.'.'.$routeKey, $parameters);
        } catch (Throwable) {
            return route($localeSlug.'.start.index');
        }
    }
}

if (! function_exists('zunscan_route')) {
    /**
     * Zunscan's own version of localized_route(): its route names are
     * "zunscan.{locale}.{rest}" rather than "{locale}.{rest}", so the
     * locale slug has to be inserted after the "zunscan." prefix instead
     * of standing in front of it.
     *
     * Defaults to a relative path — deliberately, not just for convenience.
     * AppServiceProvider forces every *absolute* route()/url() call app-wide
     * to config('app.url') (www.codebar.ch), because that is correct for the
     * main site. Zunscan lives on a different host, so an absolute call here
     * would silently point at the wrong domain; a relative path sidesteps
     * that forced root entirely and still resolves correctly in the browser.
     */
    function zunscan_route(string $name, mixed $parameters = [], bool $absolute = false): string
    {
        $locale = Str::slug(app()->getLocale());

        return route('zunscan.'.$locale.'.'.$name, $parameters, $absolute);
    }
}

if (! function_exists('zunscan_locale_switch_url')) {
    /**
     * The current Zunscan page's URL in another locale, as a real absolute URL
     * (hreflang tags and the switcher both need one). Built from the request's
     * own host rather than route()'s absolute mode, for the same reason as
     * zunscan_route() above.
     */
    function zunscan_locale_switch_url(string $locale): string
    {
        $localeSlug = Str::slug($locale);
        $routeName = (string) request()->route()?->getName();
        $prefix = 'zunscan.'.Str::slug(app()->getLocale()).'.';
        $host = request()->getSchemeAndHttpHost();

        if (! Str::startsWith($routeName, $prefix)) {
            return $host.route('zunscan.'.$localeSlug.'.start.index', absolute: false);
        }

        $routeKey = Str::after($routeName, $prefix);

        try {
            return $host.route('zunscan.'.$localeSlug.'.'.$routeKey, absolute: false);
        } catch (Throwable) {
            return $host.route('zunscan.'.$localeSlug.'.start.index', absolute: false);
        }
    }
}
