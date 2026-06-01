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

if (! function_exists('site_configuration')) {
    function site_configuration(): object
    {
        return (object) [
            'key' => config('site.key'),
            'company' => config('site.company'),
            'company_primary_color' => config('site.company_primary_color'),
            'component_intro' => config('site.component_intro'),
            'section_news' => config('site.sections.news'),
            'section_products' => config('site.sections.products'),
            'section_services' => config('site.sections.services'),
            'section_technologies' => config('site.sections.technologies'),
            'section_open_source' => config('site.sections.open_source'),
            'section_co_working' => config('site.sections.co_working'),
            'contact_email' => config('site.contact.email'),
            'contact_phone' => config('site.contact.phone'),
        ];
    }
}
