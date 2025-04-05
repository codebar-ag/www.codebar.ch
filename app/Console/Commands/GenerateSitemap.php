<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected Sitemap $sitemap;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    public function handle(): void
    {
        $filePath = public_path('sitemap.xml');

        $this->sitemap = Sitemap::create();
        $this->addSites();
        $this->sitemap->writeToFile($filePath);
    }

    private function addSites()
    {
        $this->sitemap->add(Url::create(route('start.index')));
        $this->sitemap->add(Url::create(route('news.index')));

        News::all()->each(function (News $news) {
            $this->sitemap->add(Url::create(route('news.show', $news)));
        });

        $this->sitemap->add(Url::create(route('about-us.index')));
        $this->sitemap->add(Url::create(route('services.index')));

        Service::all()->each(function (Service $service) {
            $this->sitemap->add(Url::create(route('services.show', $service)));
        });

        $this->sitemap->add(Url::create(route('products.index')));

        Product::all()->each(function (Product $product) {
            $this->sitemap->add(Url::create(route('products.show', $product)));
        });

        $this->sitemap->add(Url::create(route('privacy.index')));
        $this->sitemap->add(Url::create(route('terms.index')));
        $this->sitemap->add(Url::create(route('imprint.index')));
        $this->sitemap->add(Url::create(route('jobs.index')));
        $this->sitemap->add(Url::create(route('media.index')));
        $this->sitemap->add(Url::create(route('contact.index')));
    }
}
