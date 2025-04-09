<?php

namespace App\Http\Controllers\Sitemap;

use App\Actions\PageAction;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use App\Sitemap\LocalizedUrlFactory;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    protected Sitemap $sitemap;

    protected Carbon $lastModificationDate;

    public function __construct()
    {
        $this->lastModificationDate = now()->startOfMonth();
    }

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
        return response($this->buildSitemap(LocaleEnum::DE)->render())
            ->header('Content-Type', 'application/xml');
    }

    public function enCH(): Response
    {
        return response($this->buildSitemap(LocaleEnum::EN)->render())
            ->header('Content-Type', 'application/xml');
    }

    private function buildSitemap(LocaleEnum $localeEnum): Sitemap
    {
        $locale = $localeEnum->value;
        $key = 'cached_sitemap_'.$localeEnum->value.'_xml';

        return Cache::remember($key, now()->addWeek(), function () use ($locale) {

            $sitemap = Sitemap::create();

            $route = 'start.index';
            $page = (new PageAction(key: $route, locale: $locale))->default();

            $sitemap->add(
                LocalizedUrlFactory::create(
                    localized_route('start.index', [], true, $locale),
                    [
                        'de' => localized_route('start.index', [], true, 'de'),
                        'en' => localized_route('start.index', [], true, 'en'),
                    ]
                )
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($this->lastModificationDate)
                    ->addImage($page->image, $page->title)
            );

            News::where('locale', $locale)->get()->each(function (News $news) use ($sitemap, $locale) {

                $page = (new PageAction(key: null, locale: $locale))->news($news);

                $sitemap->add(
                    Url::create(localized_route('news.show', ['locale' => $locale, 'news' => $news], true, $locale))
                        ->setPriority(1.0)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($this->lastModificationDate)
                        ->addImage($page->image, $page->title)
                );
            });

            // $sitemap->add(Url::create(localized_route('about-us.index', [], true, $locale)));
            $sitemap->add(Url::create(localized_route('services.index', [], true, $locale)));

            Service::where('locale', $locale)->get()->each(function (Service $service) use ($sitemap, $locale) {

                $page = (new PageAction(key: null, locale: $locale))->services($service);

                $sitemap->add(
                    Url::create(localized_route('services.show', ['locale' => $locale, 'service' => $service], true, $locale))
                        ->setPriority(1.0)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($this->lastModificationDate)
                        ->addImage($page->image, $page->title)
                );
            });

            $route = 'products.index';
            $page = (new PageAction(key: $route, locale: $locale))->default();

            $sitemap->add(
                Url::create(localized_route($route, [], true, $locale))
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($this->lastModificationDate)
                    ->addImage($page->image, $page->title)
            );

            Product::where('locale', $locale)->get()->each(function (Product $product) use ($sitemap, $locale) {

                $page = (new PageAction(key: null, locale: $locale))->products($product);

                $sitemap->add(
                    Url::create(localized_route('products.show', ['locale' => $locale, 'product' => $product], true, $locale))
                        ->setPriority(1.0)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($this->lastModificationDate)
                        ->addImage($page->image, $page->title)
                );
            });

            // $sitemap->add(Url::create(localized_route('legal.privacy.index', [], true, $locale)));
            // $sitemap->add(Url::create(localized_route('legal.terms.index', [], true, $locale)));

            $route = 'legal.imprint.index';
            $page = (new PageAction(key: $route, locale: $locale))->default();

            $sitemap->add(
                Url::create(localized_route($route, [], true, $locale))
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($this->lastModificationDate)
                    ->addImage($page->image, $page->title)
            );

            // $sitemap->add(Url::create(localized_route('jobs.index', [], true, $locale)));
            // $sitemap->add(Url::create(localized_route('media.index', [], true, $locale)));

            $route = 'contact.index';
            $page = (new PageAction(key: $route, locale: $locale))->default();

            $sitemap->add(
                Url::create(localized_route($route, [], true, $locale))
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($this->lastModificationDate)
                    ->addImage($page->image, $page->title)
            );

            return $sitemap;
        });
    }
}
