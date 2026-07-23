<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

trait HasLocalizedRouteBinding
{
    public static function registerLocalizedBinding(string $parameter): void
    {
        /** @var class-string<static> $modelClass */
        $modelClass = static::class;

        Route::bind(key: $parameter, binder: function (string $value) use ($modelClass): Model {
            return $modelClass::query()
                ->where('slug', $value)
                ->firstOrFail();
        });
    }
}
