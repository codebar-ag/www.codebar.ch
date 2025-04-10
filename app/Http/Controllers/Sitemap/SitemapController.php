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
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    protected ?SitemapBuilder $sitemap = null;

    protected const array DEFAULT_ROUTES = [
        'start.index',
        'products.index',
        'services.index',
        'contact.index',
        'legal.imprint.index',
    ];

    protected const array DEFAULT_LOCALES = [
        LocaleEnum::DE->value,
        LocaleEnum::EN->value,
    ];

    public function __invoke(): Response
    {
        $content = Cache::rememberForever(key: 'sitemap_xml', callback: function (): string {
            $this->sitemap = new SitemapBuilder;
            $this->builder();

            return $this->sitemap->toXml();
        });

        return response(content: $content)
            ->header('Content-Type', 'application/xml');
    }

    private function builder(): void
    {
        $this->addDefaultRoutesToSitemap();

        News::whereNotNull('published_at')
            ->with('references')
            ->each(fn (News $news): mixed => $this->addLocalizedPageSet(
                page: (new PageAction(locale: null, routeName: null))->news(news: $news, withReferences: true),
            ));

        Service::where('published', true)
            ->with('references')
            ->each(fn (Service $service): mixed => $this->addLocalizedPageSet(
                page: (new PageAction(locale: null, routeName: null))->service(service: $service, withReferences: true),
            ));

        Product::where('published', true)
            ->with('references')
            ->each(fn (Product $product): mixed => $this->addLocalizedPageSet(
                page: (new PageAction(locale: null, routeName: null))->product(product: $product, withReferences: true),
            ));
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

            $pages->each(fn (PageDTO $page): mixed => $this->sitemap->addItem(page: $page));
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
