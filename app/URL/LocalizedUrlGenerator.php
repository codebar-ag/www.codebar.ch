<?php

namespace App\URL;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Str;

class LocalizedUrlGenerator extends UrlGenerator
{
    public function route($name, $parameters = [], $absolute = true)
    {
        dd('hi');

        if (! str_contains($name, '.') || ! str_starts_with($name, app()->getLocale().'.')) {
            $name = Str::slug(app()->getLocale()).'.'.$name;
        }

        return parent::route($name, $parameters, $absolute);
    }
}
