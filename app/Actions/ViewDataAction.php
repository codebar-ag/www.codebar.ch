<?php

namespace App\Actions;

use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class ViewDataAction
{
    public function products()
    {
        return Cache::rememberForever('products_published', function () {
            return Product::where('published', true)->orderBy('order')->get();
        });
    }

    public function services()
    {
        return Cache::rememberForever('services_published', function () {
            return Service::where('published', true)->orderBy('order')->get();
        });
    }

    public function news()
    {
        return Cache::rememberForever('news_published', function () {
            return News::whereNotNull('published_at')->orderByDesc('published_at')->get();
        });
    }
}
