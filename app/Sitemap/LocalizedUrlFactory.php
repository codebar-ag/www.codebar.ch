<?php

namespace App\Sitemap;

use Spatie\Sitemap\Tags\Url;

class LocalizedUrlFactory
{
    /**
     * Create a localized URL with hreflang alternate links.
     */
    public static function create(string $mainUrl, array $alternateUrls = []): Url
    {
        $url = Url::create($mainUrl);

        foreach ($alternateUrls as $locale => $href) {
            $url->addTag(
                'xhtml:link',
                [
                    'rel' => 'alternate',
                    'hreflang' => $locale,
                    'href' => $href,
                ]
            );
        }

        return $url;
    }
}
