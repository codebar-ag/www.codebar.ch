<?php

namespace App\View\Components;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app')->with([
            'services' => $this->services(),
            'products' => $this->products(),
        ]);
    }

    private function products()
    {
        return Cache::rememberForever('products', function () {
            return Product::orderBy('order')->get();
        });
    }

    private function services()
    {
        return Cache::rememberForever('products', function () {
            return Service::orderBy('order')->get();
        });
    }
}
