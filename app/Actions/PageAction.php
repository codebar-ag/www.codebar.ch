<?php

namespace App\Actions;

use App\DTO\PageDTO;
use App\Models\Network;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PageAction
{
    /**
     * @param  Collection<int, PageDTO>|null  $referencePages
     */
    public function __construct(
        private ?string $locale = null,
        private ?string $routeName = null,
        private mixed $routeParameters = [],
        private ?Collection $referencePages = null,
    ) {
        $this->locale = $locale ?? app()->getLocale();
    }

    public function default(): ?PageDTO
    {
        $page = $this->findPage();

        if (! $page) {
            return null;
        }

        return new PageDTO(
            locale: $page->locale,
            routeKey: $page->key,
            routeName: Str::slug($page->locale).'.'.$this->routeName,
            title: $page->title,
            robots: $page->robots,
            description: $page->description,
            image: $page->image,
            lastModificationDate: Carbon::parse($page->updated_at ?? now()),
            routeParameters: $this->routeParameters,
            referencePages: $this->referencePages
        );
    }

    public function news(News $news, bool $withReferences = false, ?string $locale = null): PageDTO
    {
        return new PageDTO(
            locale: $locale ?? $news->locale->value,
            routeKey: 'news.show',
            routeName: Str::slug(title: $locale ?? $news->locale->value).'.news.show',
            title: $news->title,
            description: $news->teaser,
            image: $news->image,
            lastModificationDate: Carbon::parse($news->updated_at ?? now()),
            routeParameters: ['locale' => $news->locale, 'news' => $news],
            referencePages: $withReferences ? $this->newsReferencePages($news) : null,
        );
    }

    public function network(Network $network, ?string $locale = null): PageDTO
    {
        return new PageDTO(
            locale: $locale ?? $network->locale->value,
            routeKey: 'network.show',
            routeName: Str::slug(title: $locale ?? $network->locale->value).'.network.show',
            title: $network->name,
            description: $network->excerpt,
            image: $network->logo,
            lastModificationDate: Carbon::parse($network->updated_at ?? now()),
            routeParameters: ['slug' => $network->page_slug],
            referencePages: null,
        );
    }

    public function product(Product $product, bool $withReferences = false, ?string $locale = null): PageDTO
    {
        return new PageDTO(
            locale: $locale ?? $product->locale->value,
            routeKey: 'products.show',
            routeName: Str::slug(title: $locale ?? $product->locale->value).'.products.show',
            title: $product->name,
            description: $product->teaser,
            image: $product->image,
            lastModificationDate: Carbon::parse($product->updated_at ?? now()),
            routeParameters: ['locale' => $product->locale, 'product' => $product],
            referencePages: $withReferences ? $this->productReferencePages($product) : null,
        );
    }

    public function service(Service $service, bool $withReferences = false, ?string $locale = null): PageDTO
    {
        return new PageDTO(
            locale: $locale ?? $service->locale->value,
            routeKey: 'services.show',
            routeName: Str::slug(title: $locale ?? $service->locale->value).'.services.show',
            title: $service->name,
            description: $service->teaser,
            image: $service->image,
            lastModificationDate: Carbon::parse($service->updated_at ?? now()),
            routeParameters: ['locale' => $service->locale, 'service' => $service],
            referencePages: $withReferences ? $this->serviceReferencePages($service) : null,
        );
    }

    public function technology(Technology $technology, bool $withReferences = false, ?string $locale = null): PageDTO
    {
        return new PageDTO(
            locale: $locale ?? $technology->locale->value,
            routeKey: 'technologies.show',
            routeName: Str::slug(title: $locale ?? $technology->locale->value).'.technologies.show',
            title: $technology->title,
            description: $technology->teaser,
            image: $technology->image,
            lastModificationDate: Carbon::parse($technology->updated_at ?? now()),
            routeParameters: ['locale' => $technology->locale, 'technology' => $technology],
            referencePages: $withReferences ? $this->technologyReferencePages($technology) : null,
        );
    }

    public function openSource(OpenSource $openSource, bool $withReferences = false, ?string $locale = null): PageDTO
    {
        return new PageDTO(
            locale: $locale ?? $openSource->locale->value,
            routeKey: 'open-source.show',
            routeName: Str::slug(title: $locale ?? $openSource->locale->value).'.open-source.show',
            title: $openSource->title,
            description: $openSource->teaser,
            image: $openSource->image,
            lastModificationDate: Carbon::parse($openSource->updated_at ?? now()),
            routeParameters: ['locale' => $openSource->locale, 'openSource' => $openSource],
            referencePages: $withReferences ? $this->openSourceReferencePages($openSource) : null,
        );
    }

    /**
     * @return Collection<int, PageDTO>
     */
    private function newsReferencePages(News $news): Collection
    {
        $pages = [];

        foreach ($news->references as $reference) {
            $reference->load(['target']);

            if (! $reference->target instanceof News) {
                continue;
            }

            $pages[] = $this->news(news: $reference->target, withReferences: false, locale: $reference->reference_locale);
        }

        return collect($pages);
    }

    /**
     * @return Collection<int, PageDTO>
     */
    private function productReferencePages(Product $product): Collection
    {
        $pages = [];

        foreach ($product->references as $reference) {
            $reference->load(['target']);

            if (! $reference->target instanceof Product) {
                continue;
            }

            $pages[] = $this->product(product: $reference->target, withReferences: false, locale: $reference->reference_locale);
        }

        return collect($pages);
    }

    /**
     * @return Collection<int, PageDTO>
     */
    private function serviceReferencePages(Service $service): Collection
    {
        $pages = [];

        foreach ($service->references as $reference) {
            $reference->load(['target']);

            if (! $reference->target instanceof Service) {
                continue;
            }

            $pages[] = $this->service(service: $reference->target, withReferences: false, locale: $reference->reference_locale);
        }

        return collect($pages);
    }

    /**
     * @return Collection<int, PageDTO>
     */
    private function technologyReferencePages(Technology $technology): Collection
    {
        $pages = [];

        foreach ($technology->references as $reference) {
            $reference->load(['target']);

            if (! $reference->target instanceof Technology) {
                continue;
            }

            $pages[] = $this->technology(technology: $reference->target, withReferences: false, locale: $reference->reference_locale);
        }

        return collect($pages);
    }

    /**
     * @return Collection<int, PageDTO>
     */
    private function openSourceReferencePages(OpenSource $openSource): Collection
    {
        $pages = [];

        foreach ($openSource->references as $reference) {
            $reference->load(['target']);

            if (! $reference->target instanceof OpenSource) {
                continue;
            }

            $pages[] = $this->openSource(openSource: $reference->target, withReferences: false, locale: $reference->reference_locale);
        }

        return collect($pages);
    }

    private function findPage(): ?Page
    {
        return Page::where('locale', $this->locale)
            ->where('key', $this->routeName)
            ->first();
    }
}
