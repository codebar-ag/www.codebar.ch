<?php

declare(strict_types=1);

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ProductsShowController extends Controller
{
    public function __invoke(string $locale, Product $product): RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');
    }
}
