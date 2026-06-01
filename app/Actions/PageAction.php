<?php

namespace App\Actions;

use App\Content\ContentItem;
use App\DTO\PageDTO;

class PageAction
{
    public static function for(string $routeName, ?string $locale = null): PageDTO
    {
        $locale ??= app()->getLocale();

        return new PageDTO(
            locale: $locale,
            title: self::translate("pages.{$routeName}.title", $locale, default: config('site.company')),
            description: self::translate("pages.{$routeName}.description", $locale),
            url: request()->url(),
            lastModificationDate: now()->startOfMonth(),
        );
    }

    public static function fromContent(ContentItem $item, ?string $locale = null): PageDTO
    {
        return new PageDTO(
            locale: $locale ?? $item->locale->value,
            title: $item->title,
            description: $item->teaser,
            image: $item->image,
            url: request()->url(),
            lastModificationDate: $item->publishedAt ?? now()->startOfMonth(),
        );
    }

    private static function translate(string $key, string $locale, ?string $default = null): ?string
    {
        $translation = trans($key, [], $locale);

        return $translation === $key ? $default : $translation;
    }
}
