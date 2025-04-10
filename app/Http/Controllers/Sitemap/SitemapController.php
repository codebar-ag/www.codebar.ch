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
        $sitemap = Cache::rememberForever('sitemap_xml', function () {
            $this->sitemap = new SitemapBuilder;
            $this->builder();

            return $this->sitemap->toXml();
        });

        return response($sitemap)
            ->header('Content-Type', 'application/xml');

    }

    private function builder(): void
    {
        $this->addDefaultRoutesToSitemap();

        News::whereNotNull('published_at')
            ->with('references')
            ->each(fn (News $news) => $this->addLocalizedPageSet(
                page: (new PageAction(locale: null, routeName: null))->news($news, withReferences: true),
            ));

        Service::where('published', true)
            ->with('references')
            ->each(fn (Service $service) => $this->addLocalizedPageSet(
                page: (new PageAction(locale: null, routeName: null))->service($service, withReferences: true),
            ));

        Product::where('published', true)
            ->with('references')
            ->each(fn (Product $product) => $this->addLocalizedPageSet(
                page: (new PageAction(locale: null, routeName: null))->product($product, withReferences: true),
            ));
    }

    private function addDefaultRoutesToSitemap(): void
    {
        collect(self::DEFAULT_ROUTES)->each(function ($routeName) {

            $pages = collect(self::DEFAULT_LOCALES)->map(function ($locale) use ($routeName) {
                return (new PageAction(locale: $locale, routeName: $routeName))->default();
            })->filter();

            $pages->each(function ($page) use ($pages) {
                $page->referencePages = $pages;
            });

            $pages->each(fn (PageDTO $page) => $this->sitemap->addItem($page));
        });
    }

    private function addLocalizedPageSet(PageDTO $page): void
    {
        $pages = collect($page->referencePages)->prepend($page)->unique(fn (PageDTO $p) => $p->locale);

        $pages->each(function (PageDTO $page) use ($pages) {
            $page->referencePages = collect([$page])
                ->merge($pages->reject(fn ($ref) => $ref->locale === $page->locale))
                ->values();

            $this->sitemap->addItem(page: $page);
        });
    }
}
