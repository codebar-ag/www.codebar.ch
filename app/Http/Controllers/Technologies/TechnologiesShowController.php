<?php

namespace App\Http\Controllers\Technologies;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TechnologiesShowController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(string $locale, Product $product): View
    {
        return view('app.products.show')->with([
            'page' => (new PageAction(locale: $locale))->product(product: $product),
            'name' => $product->name,
            'teaser' => $product->teaser,
            'content' => Str::of($product->content)->markdown(),
            'tags' => $product->tags,
        ]);
    }
}
