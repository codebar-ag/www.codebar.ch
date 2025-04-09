<?php

namespace App\Actions;

use App\DTO\PageDTO;
use App\Models\News;
use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PageAction
{
    private string $prefix;

    public function __construct(
        private ?string $key = null,
        private ?string $locale = null,
    ) {
        $this->locale = $locale ?? app()->getLocale();
        $this->prefix = 'cached_page_'.$this->locale.'_';
    }

    public function default(): ?PageDTO
    {
        $key = Str::slug($this->prefix.$this->key);

        return Cache::remember($key, 86400, function () {
            $page = $this->findPage();

            if (! $page) {
                return null;
            }

            return new PageDTO(
                locale: $page->locale,
                routeName: $this->key,
                title: $page->title,
                description: $page->description,
                image: $page->image,
                lastModificationDate: $page->updated_at ?? now(),
                routeParameters: ['locale' => $page->locale],
                referencePages: new Collection
            );
        });
    }

    public function news(News $news): PageDTO
    {
        return new PageDTO(
            locale: $news->locale->value,
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
            ->where('key', $this->key)
            ->first();
    }
}
