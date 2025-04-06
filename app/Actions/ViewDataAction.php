<?php

namespace App\Actions;

use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ViewDataAction
{
    public function products(): Collection
    {
        $key = Str::slug('products_published');

        return Cache::rememberForever($key, function () {
            return Product::where('published', true)->orderBy('order')->get();
        });
    }

    public function services(): Collection
    {
        $key = Str::slug('services_published');

        return Cache::rememberForever($key, function () {
            return Service::where('published', true)->orderBy('order')->get();
        });
    }

    public function news(string $locale): Collection
    {
        $key = Str::slug("news_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return News::where('locale', $locale)->whereNotNull('published_at')->orderByDesc('published_at')->get();
        });
    }
}
