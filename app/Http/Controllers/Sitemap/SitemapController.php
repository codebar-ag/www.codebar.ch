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
            $sitemap->add(Url::create(route('sitemap-de-ch')));
            $sitemap->add(Url::create(route('sitemap-en-ch')));

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

            $sitemap->add(Url::create(route('start.index')));

            News::where('locale', $locale)->get()->each(function (News $news) use ($sitemap) {
                $sitemap->add(Url::create(route('news.show', $news)));
            });

            // $sitemap->add(Url::create(route('about-us.index')));
            $sitemap->add(Url::create(route('services.index')));

            Service::where('locale', $locale)->get()->each(function (Service $service) use ($sitemap) {
                $sitemap->add(Url::create(route('services.show', $service)));
            });

            $sitemap->add(Url::create(route('products.index')));

            Product::where('locale', $locale)->get()->each(function (Product $product) use ($sitemap) {
                $sitemap->add(Url::create(route('products.show', $product)));
            });

            $sitemap->add(Url::create(route('legal.privacy.index')));
            $sitemap->add(Url::create(route('legal.terms.index')));
            $sitemap->add(Url::create(route('legal.imprint.index')));

            // $sitemap->add(Url::create(route('jobs.index')));
            // $sitemap->add(Url::create(route('media.index')));
            $sitemap->add(Url::create(route('contact.index')));

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

            $sitemap->add(Url::create(route('start.index')));

            News::where('locale', $locale)->get()->each(function (News $news) use ($sitemap) {
                $sitemap->add(Url::create(route('news.show', $news)));
            });

            // $sitemap->add(Url::create(route('about-us.index')));
            $sitemap->add(Url::create(route('services.index')));

            Service::where('locale', $locale)->get()->each(function (Service $service) use ($sitemap) {
                $sitemap->add(Url::create(route('services.show', $service)));
            });

            $sitemap->add(Url::create(route('products.index')));

            Product::where('locale', $locale)->get()->each(function (Product $product) use ($sitemap) {
                $sitemap->add(Url::create(route('products.show', $product)));
            });

            $sitemap->add(Url::create(route('legal.privacy.index')));
            $sitemap->add(Url::create(route('legal.terms.index')));
            $sitemap->add(Url::create(route('legal.imprint.index')));

            // $sitemap->add(Url::create(route('jobs.index')));
            // $sitemap->add(Url::create(route('media.index')));
            $sitemap->add(Url::create(route('contact.index')));

            return $sitemap;
        });

        return response($sitemap->render())
            ->header('Content-Type', 'application/xml');

    }
}
