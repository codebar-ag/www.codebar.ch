<?php

namespace App\Sitemap;

use App\Enums\LocaleEnum;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SimpleXMLElement;

class SitemapBuilder
{
    protected SimpleXMLElement $xml;

    public static function make(): self
    {
        $self = new self;
        $self->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset/>');
        $self->xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $self->xml->addAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        $self->xml->addAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');

        return $self;
    }

    public function addItem(string $route, Carbon $lastModificationDate, string $imageTitle, string $imageUrl, Collection $references)
    {
        $url = $this->xml->addChild('url');
        $url->addChild('loc', $route);

        $url->addChild('lastmod', $lastModificationDate->format('c'));

        $img = $url->addChild('image:image', '', 'http://www.google.com/schemas/sitemap-image/1.1');
        $img->addChild('image:loc', $imageUrl, 'http://www.google.com/schemas/sitemap-image/1.1');
        $img->addChild('image:caption', $imageTitle, 'http://www.google.com/schemas/sitemap-image/1.1');

        return $this;
    }

    public function addItemOld(
        object $item,
        string $routeName,
        ?\DateTimeInterface $lastmod = null,
        ?string $image = null,
        ?string $title = null,
        ?string $locale = null,
        ?string $paramName = null
    ): self {
        $locale ??= $item->locale ?? app()->getLocale();
        $paramName ??= Str::slug(class_basename($item));

        $params = ['locale' => $locale, $paramName => $item];
        $loc = localized_route($routeName, $params, true, $locale);

        if ($lastmod) {

        }

        if ($image) {

        }

        // hreflang-Verlinkung nur für Modelle mit translatedTo()
        if (method_exists($item, 'translatedTo')) {
            foreach (LocaleEnum::cases() as $alt) {
                $translated = $item->translatedTo($alt->value);
                if (! $translated) {
                    continue;
                }

                $href = localized_route($routeName, ['locale' => $alt->value, $paramName => $translated], true, $alt->value);
                $link = $url->addChild('xhtml:link', null, 'http://www.w3.org/1999/xhtml');
                $link->addAttribute('rel', 'alternate');
                $link->addAttribute('hreflang', $alt->value);
                $link->addAttribute('href', $href);
            }
        }

        return $this;
    }

    public function toXml(): string
    {
        return $this->xml->asXML();
    }
}
