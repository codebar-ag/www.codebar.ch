<?php

declare(strict_types=1);

namespace App\Http\Controllers\Products;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductsShowController extends Controller
{
    public function __invoke(string $locale, Product $product): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        return view('app.products.show')->with([
                    'page' => (new PageAction(locale: $locale))->product(product: $product),
                    'name' => $product->name,
                    'headline' => $product->headline,
                    'teaser' => $product->teaser,
                    'content' => Str::of($product->content ?? '')->markdown(),
                    'tags' => $product->tags,
                    'featuresHeading' => $product->features_heading,
                    'featuresIntro' => $product->features_intro,
                    'features' => $product->features,
                    'deploymentHeading' => $product->deployment_heading,
                    'deploymentIntro' => $product->deployment_intro,
                    'deploymentOptions' => $product->deployment_options,
                    'ctaHeading' => $product->cta_heading,
                    'ctaBody' => $product->cta_body,
                ]);*/
    }
}
