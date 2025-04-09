<?php

namespace App\Actions;

use App\DTO\PageDTO;
use App\Models\News;
use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PageAction
{
    public function __construct(
        private ?string $locale = null,
        private ?string $routeName = null,
        private mixed $routeParameters = [],
        private Collection $referencePages = new Collection
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
            description: $page->description,
            image: $page->image,
            lastModificationDate: $page->updated_at ?? now(),
            routeParameters: $this->routeParameters,
            referencePages: $this->referencePages
        );
    }

    public function news(News $news): PageDTO
    {
        return new PageDTO(
            locale: $news->locale->value,
            routeKey: 'news.show',
            routeName: 'news.show',
            title: $news->title,
            description: $news->teaser,
            image: $news->image,
            lastModificationDate: $news->updated_at ?? now(),
            routeParameters: ['locale' => $news->locale, 'news' => $news],
            referencePages: new Collection
        );
    }

    public function products(Product $product): PageDTO
    {
        return new PageDTO(
            locale: $product->locale->value,
            routeKey: 'products.show',
            routeName: 'products.show',
            title: $product->name,
            description: $product->teaser,
            image: $product->image,
            lastModificationDate: $product->updated_at ?? now(),
            routeParameters: ['locale' => $product->locale, 'product' => $product],
            referencePages: new Collection
        );
    }

    public function services(Service $service): PageDTO
    {
        return new PageDTO(
            locale: $service->locale->value,
            routeKey: 'services.show',
            routeName: 'services.show',
            title: $service->name,
            description: $service->teaser,
            image: $service->image,
            lastModificationDate: $service->updated_at ?? now(),
            routeParameters: ['locale' => $service->locale, 'service' => $service],
            referencePages: new Collection
        );
    }

    private function findPage(): ?Page
    {
        return Page::where('locale', $this->locale)
            ->where('key', $this->routeName)
            ->first();
    }
}
