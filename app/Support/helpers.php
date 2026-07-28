<?php

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
        $parameters = $route?->parameters() ?? [];

        // Detail routes carry the locale in the path as well as the prefix.
        if (array_key_exists('locale', $parameters)) {
            $parameters['locale'] = $locale;
        }

        try {
            return route($localeSlug.'.'.$routeKey, $parameters);
        } catch (Throwable) {
            return route($localeSlug.'.start.index');
        }
    }
}
