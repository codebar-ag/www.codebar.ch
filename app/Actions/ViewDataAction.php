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
        return Cache::rememberForever('products', function () {
            return Product::where('published', true)->orderBy('order')->get();
        });
    }

    public function services()
    {
        return Cache::rememberForever('services', function () {
            return Service::where('published', true)->orderBy('order')->get();
        });
    }

    public function news()
    {
        return Cache::rememberForever('news', function () {
            return News::where('published', true)->orderByDesc('published_at')->get();
        });
    }
}
