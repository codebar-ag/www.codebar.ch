<?php

namespace App\Actions;

use App\Models\News;
use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
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

    public function default(): ?Page
    {
        Str::slug($key = $this->prefix.$this->key);

        return Cache::remember($key, 86400, fn () => $this->findPage());
    }

    public function news(News $news): ?Page
    {
        return $this->createFakePage(
            key: Str::slug($this->prefix.'news_'.$news->id),
            title: $news->title,
            description: $news->teaser,
            image: $news->image
        );
    }

    public function products(Product $product): ?Page
    {
        return $this->createFakePage(
            key: Str::slug($this->prefix.'products_'.$product->id),
            title: $product->name,
            description: $product->teaser,
            image: $product->image
        );
    }

    public function services(Service $service): ?Page
    {
        return $this->createFakePage(
            key: Str::slug($this->prefix.'services_'.$service->id),
            title: $service->name,
            description: $service->teaser,
            image: $service->image,
        );
    }

    private function createFakePage(
        string $key,
        string $title,
        string $description,
        mixed $image
    ): ?Page {
        return Cache::rememberForever($key, function () use ($title, $description, $image) {

            $fakePage = new Page;
            $fakePage->locale = app()->getLocale();
            $fakePage->robots = 'index,follow';
            $fakePage->title = $title;
            $fakePage->description = $description;
            $fakePage->image = $image;

            return $fakePage;
        });
    }

    private function findPage(): ?Page
    {
        return Page::where('locale', $this->locale)->where('key', $this->key)->first();
    }
}
