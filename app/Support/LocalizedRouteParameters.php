<?php

namespace App\Support;

use App\Contracts\HasTranslatedRouteKey;
use Illuminate\Database\Eloquent\Model;

/**
 * Rewrites route parameters for another language.
 *
 * Detail routes carry the locale twice — once in the route-name prefix and once as a path
 * segment (/aktuelles/{locale}/{slug}). Since slugs are translated, swapping only the
 * locale segment would leave the German URL pointing at the English slug.
 */
class LocalizedRouteParameters
{
    /**
     * @param  array<string, mixed>  $parameters
     * @return array<string, mixed>
     */
    public static function for(array $parameters, string $locale): array
    {
        foreach ($parameters as $name => $value) {
            if ($name === 'locale') {
                $parameters[$name] = $locale;

                continue;
            }

            if ($value instanceof Model) {
                $parameters[$name] = self::routeKeyFor($value, $locale);
            }
        }

        return $parameters;
    }

    private static function routeKeyFor(Model $model, string $locale): mixed
    {
        if (! $model instanceof HasTranslatedRouteKey) {
            return $model->getRouteKey();
        }

        $slug = $model->getTranslation($model->getRouteKeyName(), $locale);

        // An article without a translation for this language keeps its default key
        // rather than producing a URL with an empty segment.
        return is_string($slug) && $slug !== '' ? $slug : $model->getRouteKey();
    }
}
