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
