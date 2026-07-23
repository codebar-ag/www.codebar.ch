<?php

namespace App\Http\Controllers\Sitemap;

use App\Actions\PageAction;
use App\DTO\PageDTO;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Sitemap\SitemapBuilder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    protected const array DEFAULT_ROUTES = [
        'start.index',
        'services.index',
        'contact.index',
        'media.index',
        'legal.imprint.index',
        'legal.privacy.index',
        'legal.terms.index',
        'jobs.index',
        'ai.index',
        'ai.llm.index',
        'ai.llm.analytics.index',
        'network.index',
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
        $this->addNetworksToSitemap($sitemap);
    }

    private function addNetworksToSitemap(SitemapBuilder $sitemap): void
    {
        Network::query()
            ->where('published', true)
            ->whereNotNull('page_slug')
            ->get()
            ->each(function (Network $network) use ($sitemap): void {
                $action = new PageAction(locale: null, routeName: null);

                $pages = collect(self::DEFAULT_LOCALES)
                    ->map(fn (string $locale): PageDTO => $action->network(network: $network, locale: $locale))
                    ->values();

                $pages->each(function (PageDTO $page) use ($pages): void {
                    $page->referencePages = $pages;
                });

                $pages->each(function (PageDTO $page) use ($sitemap): void {
                    $sitemap->addItem(page: $page);
                });
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
}
