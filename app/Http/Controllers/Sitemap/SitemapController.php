<?php

namespace App\Http\Controllers\Sitemap;

use App\Actions\PageAction;
use App\DTO\PageDTO;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use App\Sitemap\SitemapBuilder;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    protected const array DEFAULT_ROUTES = [
        'start.index',
        'about-us.index',
        'news.index',
        'products.index',
        'services.index',
        'contact.index',
        'media.index',
        'legal.imprint.index',
        'legal.privacy.index',
        'ai.index',
        'ai.llm.index',
        'ai.llm.analytics.index',
    ];

    protected const array DEFAULT_LOCALES = [
        LocaleEnum::DE->value,
        LocaleEnum::EN->value,
    ];

    public function __invoke(): Response
    {
        $content = Cache::remember(
            key: 'sitemap_xml',
            ttl: now()->addHours(24),
            callback: function (): string {
                $sitemap = new SitemapBuilder;
                $this->builder($sitemap);

                return $sitemap->toXml();
            }
        );

        return response(content: $content)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600'); // Cache for 1 hour
    }

    private function builder(SitemapBuilder $sitemap): void
    {
        $this->addDefaultRoutesToSitemap($sitemap);

        // Use chunked queries to prevent memory issues
        News::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with('references')
            ->chunk(100, function (Collection $news) use ($sitemap): void {
                foreach ($news as $item) {
                    $this->addLocalizedPageSet(
                        page: (new PageAction(locale: null, routeName: null))->news(news: $item, withReferences: true),
                        sitemap: $sitemap,
                    );
                }
            });

        Service::where('published', true)
            ->with('references')
            ->chunk(100, function (Collection $services) use ($sitemap): void {
                foreach ($services as $item) {
                    $this->addLocalizedPageSet(
                        page: (new PageAction(locale: null, routeName: null))->service(service: $item, withReferences: true),
                        sitemap: $sitemap,
                    );
                }
            });

        Product::where('published', true)
            ->with('references')
            ->chunk(100, function (Collection $products) use ($sitemap): void {
                foreach ($products as $item) {
                    $this->addLocalizedPageSet(
                        page: (new PageAction(locale: null, routeName: null))->product(product: $item, withReferences: true),
                        sitemap: $sitemap,
                    );
                }
            });
    }

    private function addDefaultRoutesToSitemap(SitemapBuilder $sitemap): void
    {
        collect(value: self::DEFAULT_ROUTES)->each(function (string $routeName) use ($sitemap): void {
            $pages = collect(self::DEFAULT_LOCALES)
                ->map(function (string $locale) use ($routeName): ?PageDTO {
                    return (new PageAction(locale: $locale, routeName: $routeName))->default();
                })
                ->filter();

            $pages->each(function (PageDTO $page) use ($pages): void {
                $page->referencePages = $pages;
            });

            $pages->each(function (PageDTO $page) use ($sitemap): void {
                $sitemap->addItem(page: $page);
            });
        });
    }

    private function addLocalizedPageSet(PageDTO $page, SitemapBuilder $sitemap): void
    {
        $localizedPages = [$page];

        foreach ($page->referencePages ?? [] as $referencePage) {
            $localizedPages[] = $referencePage;
        }

        $pages = collect($localizedPages)->unique(fn (PageDTO $p): string => $p->locale)->values();

        foreach ($pages as $currentPage) {
            $otherPages = $pages->reject(fn (PageDTO $ref): bool => $ref->locale === $currentPage->locale)->values();

            $currentPage->referencePages = collect([$currentPage])->merge($otherPages)->values();

            $sitemap->addItem(page: $currentPage);
        }
    }
}
