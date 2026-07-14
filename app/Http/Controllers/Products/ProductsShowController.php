<?php

namespace App\Http\Controllers\Products;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductsShowController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(string $locale, Product $product): View
    {
        return view('app.products.show')->with([
            'page' => (new PageAction(locale: $locale))->product(product: $product),
            'name' => $product->name,
            'headline' => $product->headline,
            'teaser' => $product->teaser,
            'content' => Str::of($product->content ?? '')->markdown(),
            'tags' => $product->tags,
            'deploymentHeading' => $product->deployment_heading,
            'deploymentIntro' => $product->deployment_intro,
            'deploymentOptions' => $product->deployment_options,
            'ctaHeading' => $product->cta_heading,
            'ctaBody' => $product->cta_body,
        ]);
    }
}
