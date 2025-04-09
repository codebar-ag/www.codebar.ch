<?php

namespace App\Http\Controllers\Sitemap;

use App\Actions\PageAction;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use App\Sitemap\SitemapBuilder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    protected const array DEFAULT_ROUTES = [
        'start.index',
        'products.index',
        'services.index',
        'contact.index',
        'legal.imprint.index',
    ];

    /**
     * Display the user's profile form.
     */
    public function index(): Response
    {
        $sitemap = Cache::remember('cached_sitemap_xml', now()->addDay(), function () {
            $sitemap = Sitemap::create();
            $sitemap->add(Url::create(route('de-ch.sitemap')));
            $sitemap->add(Url::create(route('en-en.sitemap')));

            return $sitemap;
        });

        return response($sitemap->render())
            ->header('Content-Type', 'application/xml');
    }

    public function deCH(): Response
    {
        $locale = LocaleEnum::DE->value;
        $referenceLocale = LocaleEnum::EN->value;

        $sitemap = $this->defaultRoutes($locale, $referenceLocale);

        return response($sitemap)
            ->header('Content-Type', 'application/xml');
    }

    public function enCH(): Response
    {
        $locale = LocaleEnum::EN->value;
        $referenceLocale = LocaleEnum::DE->value;

        $sitemap = $this->defaultRoutes($locale, $referenceLocale);

        return response($sitemap)
            ->header('Content-Type', 'application/xml');
    }

    private function defaultRoutes(string $locale, string $referenceLocale): string
    {
        $sitemap = (new SitemapBuilder);

        $routes = collect(self::DEFAULT_ROUTES);

        $routes->each(function ($routeName) use ($sitemap, $locale, $referenceLocale) {
            $this->addItem($sitemap, $locale, $routeName, $referenceLocale);
        });

        return $sitemap->toXml();
    }

    private function addItem(SitemapBuilder $builder, string $locale, string $routeName, ?string $referenceLocale = null)
    {
        $defaultPage = (new PageAction(locale: $locale, routeName: $routeName))->default();

        if (! blank($referenceLocale)) {
            $referencePage = (new PageAction(locale: $referenceLocale, routeName: $routeName))->default();

            $page = (new PageAction(locale: $locale, routeName: $routeName, routeParameters: [], referencePages: collect([
                $defaultPage,
                $referencePage,
            ])))->default();
        }

        $builder->addItem(pageDTO: $page);
    }
}
