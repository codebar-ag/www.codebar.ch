<?php

namespace App\Actions;

use App\Models\News;
use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class PageAction
{
    private string $prefix;

    public function __construct(
        protected string $key,
    ) {
        $this->prefix = 'cached_page_'.app()->getLocale().'_';
    }

    public function default(): ?Page
    {
        $key = $this->prefix.$this->key;

        return Cache::remember($key, 86400, fn () => $this->findPage());
    }

    public function newsShow(News $news): ?Page
    {
        return $this->createFakePage(
            key: $this->prefix.'news_id'.$news->id,
            title: $news->title,
            description: $news->teaser,
            image: $news->image
        );
    }

    public function products(Product $product): ?Page
    {
        return $this->createFakePage(
            key: $this->prefix.'products_'.$product->id,
            title: $product->title,
            description: $product->teaser,
            image: $product->image
        );
    }

    public function services(Service $service): ?Page
    {
        return $this->createFakePage(
            key: $this->prefix.'services'.$service->id,
            title: $service->title,
            description: $service->teaser,
            image: $service->image
        );
    }

    private function createFakePage(
        string $key,
        string $title,
        string $description,
        string $image
    ): ?Page {
        return Cache::rememberForever($key, function () use ($title, $description, $image) {

            $fakePage = new Page;
            $fakePage->locale = app()->getLocale();
            $fakePage->robots = 'index,follow';
            $fakePage->title = $title;
            $fakePage->teaser = $description;
            $fakePage->image = $image;

            return $fakePage;
        });
    }

    private function findPage(): ?Page
    {
        return Page::where('index', $this->key)->first();
    }
}
