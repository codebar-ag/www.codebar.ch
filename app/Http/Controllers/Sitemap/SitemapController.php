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
    protected ?SitemapBuilder $sitemap = null;

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
                $this->sitemap = new SitemapBuilder;
                $this->builder();

                return $this->sitemap->toXml();
            }
        );

        return response(content: $content)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600'); // Cache for 1 hour
    }

    private function builder(): void
    {
        $this->addDefaultRoutesToSitemap();

        // Use chunked queries to prevent memory issues
        News::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with('references')
            ->chunk(100, function (Collection $news): void {
                /** @var News $item */
                foreach ($news as $item) {
                    $this->addLocalizedPageSet(
                        page: (new PageAction(locale: null, routeName: null))->news(news: $item, withReferences: true),
                    );
                }
            });

        Service::where('published', true)
            ->with('references')
            ->chunk(100, function (Collection $services): void {
                /** @var Service $item */
                foreach ($services as $item) {
                    $this->addLocalizedPageSet(
                        page: (new PageAction(locale: null, routeName: null))->service(service: $item, withReferences: true),
                    );
                }
            });

        Product::where('published', true)
            ->with('references')
            ->chunk(100, function (Collection $products): void {
                /** @var Product $item */
                foreach ($products as $item) {
                    $this->addLocalizedPageSet(
                        page: (new PageAction(locale: null, routeName: null))->product(product: $item, withReferences: true),
                    );
                }
            });
    }

    private function addDefaultRoutesToSitemap(): void
    {
        collect(value: self::DEFAULT_ROUTES)->each(function (string $routeName): void {
            $pages = collect(self::DEFAULT_LOCALES)
                ->map(function (string $locale) use ($routeName): ?PageDTO {
                    return (new PageAction(locale: $locale, routeName: $routeName))->default();
                })
                ->filter();

            $pages->each(function (PageDTO $page) use ($pages): void {
                $page->referencePages = $pages;
            });

            $pages->each(function (PageDTO $page): void {
                $this->sitemap->addItem(page: $page);
            });
        });
    }

    private function addLocalizedPageSet(PageDTO $page): void
    {
        $pages = collect(value: $page->referencePages)
            ->prepend(value: $page)
            ->unique(fn (PageDTO $p): string => $p->locale);

        $pages->each(function (PageDTO $page) use ($pages): void {
            $page->referencePages = collect(value: [$page])
                ->merge($pages->reject(fn (PageDTO $ref): bool => $ref->locale === $page->locale))
                ->values();

            $this->sitemap->addItem(page: $page);
        });
    }
}
