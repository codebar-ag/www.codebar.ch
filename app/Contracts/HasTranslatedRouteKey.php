<?php

namespace App\Contracts;

/**
 * A model whose URL slug exists once per language, so route parameters have to be
 * rebuilt when linking to another locale instead of reusing the current slug.
 *
 * Only the translation lookup is declared here — getRouteKey() and getRouteKeyName()
 * come from Eloquent\Model, which declares them without return types and would clash
 * with a typed signature.
 */
interface HasTranslatedRouteKey
{
    public function getTranslation(string $key, string $locale, bool $useFallbackLocale = true): mixed;
}
