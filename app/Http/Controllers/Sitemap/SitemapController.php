<?php

namespace App\Http\Controllers\Sitemap;

use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    protected Sitemap $sitemap;

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

    public function deCH()
    {
        $sitemap = Cache::remember('cached_sitemap_de_ch_xml', now()->addDay(), function () {

            $locale = LocaleEnum::DE->value;

            $sitemap = Sitemap::create();

            $sitemap->add(Url::create(localized_route('start.index', [], true, $locale)));

            News::where('locale', $locale)->get()->each(function (News $news) use ($sitemap, $locale) {
                $sitemap->add(Url::create(localized_route('news.show', $news, true, $locale)));
            });

            $sitemap->add(Url::create(localized_route('about-us.index', [], true, $locale)));
            $sitemap->add(Url::create(localized_route('services.index', [], true, $locale)));

            Service::where('locale', $locale)->get()->each(function (Service $service) use ($sitemap, $locale) {
                $sitemap->add(Url::create(localized_route('services.show', $service, true, $locale)));
            });

            $sitemap->add(Url::create(localized_route('products.index', [], true, $locale)));

            Product::where('locale', $locale)->get()->each(function (Product $product) use ($sitemap, $locale) {
                $sitemap->add(Url::create(localized_route('products.show', $product, true, $locale)));
            });

            // $sitemap->add(Url::create(localized_route('legal.privacy.index', [], true, $locale)));
            // $sitemap->add(Url::create(localized_route('legal.terms.index', [], true, $locale)));
            $sitemap->add(Url::create(localized_route('legal.imprint.index', [], true, $locale)));

            // $sitemap->add(Url::create(localized_route('jobs.index', [], true, $locale)));
            // $sitemap->add(Url::create(localized_route('media.index', [], true, $locale)));
            $sitemap->add(Url::create(localized_route('contact.index', [], true, $locale)));

            return $sitemap;
        });

        return response($sitemap->render())
            ->header('Content-Type', 'application/xml');

    }

    public function enCH()
    {
        $sitemap = Cache::remember('cached_sitemap_en_ch_xml', now()->addDay(), function () {

            $locale = LocaleEnum::EN->value;

            $sitemap = Sitemap::create();

            $sitemap->add(Url::create(localized_route('start.index', [], true, $locale)));

            News::where('locale', $locale)->get()->each(function (News $news) use ($sitemap, $locale) {
                $sitemap->add(Url::create(localized_route('news.show', $news, true, $locale)));
            });

            // $sitemap->add(Url::create(localized_route('about-us.index', [], true, $locale)));
            $sitemap->add(Url::create(localized_route('services.index', [], true, $locale)));

            Service::where('locale', $locale)->get()->each(function (Service $service) use ($sitemap, $locale) {
                $sitemap->add(Url::create(localized_route('services.show', $service, true, $locale)));
            });

            $sitemap->add(Url::create(localized_route('products.index', [], true, $locale)));

            Product::where('locale', $locale)->get()->each(function (Product $product) use ($sitemap, $locale) {
                $sitemap->add(Url::create(localized_route('products.show', $product, true, $locale)));
            });

            // $sitemap->add(Url::create(localized_route('legal.privacy.index', [], true, $locale)));
            // $sitemap->add(Url::create(localized_route('legal.terms.index', [], true, $locale)));
            $sitemap->add(Url::create(localized_route('legal.imprint.index', [], true, $locale)));

            // $sitemap->add(Url::create(localized_route('jobs.index', [], true, $locale)));
            // $sitemap->add(Url::create(localized_route('media.index', [], true, $locale)));
            $sitemap->add(Url::create(localized_route('contact.index', [], true, $locale)));

            return $sitemap;
        });

        return response($sitemap->render())
            ->header('Content-Type', 'application/xml');

    }
}
