<?php

namespace App\Http\Controllers\Sitemap;

use App\Content\ContentItem;
use App\Content\MarkdownContentService;
use App\DTO\PageDTO;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use App\Sitemap\SitemapBuilder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    private const array DEFAULT_ROUTES = [
        'start.index',
        'about-us.index',
        'products.index',
        'services.index',
        'technologies.index',
        'open-source.index',
        'contact.index',
        'legal.terms.index',
        'legal.imprint.index',
        'legal.privacy.index',
        'jobs.index',
        'media.index',
        'co-working.index',
    ];

    private const array CONTENT_TYPES = [
        'news' => 'news.show',
        'services' => 'services.show',
        'products' => 'products.show',
        'technologies' => 'technologies.show',
        'open-source' => 'open-source.show',
    ];

    public function __invoke(MarkdownContentService $content): Response
    {
        $body = Cache::remember('sitemap_xml', 3600, fn () => $this->build($content));

        return response($body)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    private function build(MarkdownContentService $content): string
    {
        $sitemap = new SitemapBuilder;

        foreach (self::DEFAULT_ROUTES as $routeName) {
            foreach (LocaleEnum::cases() as $locale) {
                $localeSlug = Str::slug($locale->value);
                $sitemap->addItem(new PageDTO(
                    locale: $locale->value,
                    title: trans("pages.{$routeName}.title", [], $locale->value),
                    url: route("{$localeSlug}.{$routeName}", absolute: true),
                    lastModificationDate: now()->startOfMonth(),
                ));
            }
        }

        foreach (self::CONTENT_TYPES as $type => $routeName) {
            foreach (LocaleEnum::cases() as $locale) {
                $items = $content->all($type, $locale);
                $localeSlug = Str::slug($locale->value);
                $paramKey = $this->paramKey($routeName);

                foreach ($items as $item) {
                    /** @var ContentItem $item */
                    $sitemap->addItem(new PageDTO(
                        locale: $locale->value,
                        title: $item->title,
                        description: $item->teaser,
                        image: $item->image,
                        url: route("{$localeSlug}.{$routeName}", [
                            'locale' => $locale->value,
                            $paramKey => $item->slug,
                        ], absolute: true),
                        lastModificationDate: $item->publishedAt ?? now()->startOfMonth(),
                    ));
                }
            }
        }

        return $sitemap->toXml();
    }

    private function paramKey(string $routeName): string
    {
        return match ($routeName) {
            'news.show' => 'news',
            'services.show' => 'service',
            'products.show' => 'product',
            'technologies.show' => 'technology',
            'open-source.show' => 'openSource',
            default => 'slug',
        };
    }
}
