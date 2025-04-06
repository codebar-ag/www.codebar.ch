<?php

namespace App\Http\Controllers\Sitemap;

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

    public function __construct() {}

    /**
     * Display the user's profile form.
     */
    public function __invoke(): Response
    {
        $sitemap = Cache::remember('cached_sitemap_xml', now()->addDay(), function () {

            $this->sitemap = Sitemap::create();

            $this->generate();

            return $this->sitemap;
        });

        return response($sitemap->render())
            ->header('Content-Type', 'application/xml');
    }

    private function generate()
    {
        $this->sitemap = Sitemap::create();

        $this->sitemap->add(Url::create(route('start.index')));

        News::all()->each(function (News $news) {
            $this->sitemap->add(Url::create(route('news.show', $news)));
        });

        // $this->sitemap->add(Url::create(route('about-us.index')));
        $this->sitemap->add(Url::create(route('services.index')));

        Service::all()->each(function (Service $service) {
            $this->sitemap->add(Url::create(route('services.show', $service)));
        });

        $this->sitemap->add(Url::create(route('products.index')));

        Product::all()->each(function (Product $product) {
            $this->sitemap->add(Url::create(route('products.show', $product)));
        });

        $this->sitemap->add(Url::create(route('legal.privacy.index')));
        $this->sitemap->add(Url::create(route('legal.terms.index')));
        $this->sitemap->add(Url::create(route('legal.imprint.index')));

        // $this->sitemap->add(Url::create(route('jobs.index')));
        // $this->sitemap->add(Url::create(route('media.index')));
        $this->sitemap->add(Url::create(route('contact.index')));
    }
}
