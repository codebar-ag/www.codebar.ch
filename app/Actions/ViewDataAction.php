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
    public function products(string $locale): Collection
    {
        $key = Str::slug("products_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return Product::where('locale', $locale)->where('published', true)->orderBy('order')->get();
        });
    }

    public function services(string $locale): Collection
    {
        $key = Str::slug("services_published_{$locale}");

        // dd(Service::get());
        return Cache::rememberForever($key, function () use ($locale) {
            return Service::where('locale', $locale)->where('published', true)->orderBy('order')->get();
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
