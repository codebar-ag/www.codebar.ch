<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\LocaleEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

trait HasLocalizedRouteBinding
{
    /**
     * Models that store one slug per language override this to true. The binder then
     * matches against the requested locale's slug instead of a plain column comparison.
     */
    public static function routeBindingIsTranslated(): bool
    {
        return false;
    }

    public static function registerLocalizedBinding(string $parameter): void
    {
        /** @var class-string<static> $modelClass */
        $modelClass = static::class;

        Route::bind(key: $parameter, binder: function (string $value) use ($modelClass): Model {
            if (! $modelClass::routeBindingIsTranslated()) {
                return $modelClass::query()->where('slug', $value)->firstOrFail();
            }

            $query = $modelClass::query();

            // The {locale} segment names the translation being requested; try it first so
            // /aktuelles/de_CH/… resolves the German slug even when an English one matches too.
            $requested = request()->route('locale');
            $locales = array_map(fn (LocaleEnum $case): string => $case->value, LocaleEnum::cases());

            if (is_string($requested) && in_array($requested, $locales, true)) {
                $locales = array_merge([$requested], array_diff($locales, [$requested]));
            }

            foreach ($locales as $index => $locale) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $query->{$method}('slug->'.$locale, $value);
            }

            return $query->firstOrFail();
        });
    }
}
