<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

trait HasLocalizedRouteBinding
{
    public static function registerLocalizedBinding(string $parameter): void
    {
        Route::bind($parameter, function ($value): Model {
            $locale = request()->route('locale');

            return static::query()
                ->where('locale', $locale)
                ->where('slug', $value)
                ->firstOrFail();
        });
    }
}
