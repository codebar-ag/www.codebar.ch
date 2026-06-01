<?php

namespace App\Http\Controllers\Products;

use App\Actions\PageAction;
use App\Content\MarkdownContentService;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProductsShowController extends Controller
{
    public function __invoke(MarkdownContentService $content, string $locale, string $product): View
    {
        $localeEnum = LocaleEnum::from($locale);
        $item = $content->find('products', $localeEnum, $product) ?? abort(404);

        $view = view()->exists("app.products.{$product}")
            ? "app.products.{$product}"
            : 'app.products.show';

        return view($view)->with([
            'page' => PageAction::fromContent($item),
            'name' => $item->title,
            'teaser' => $item->teaser,
            'content' => $item->body,
            'tags' => $item->tags(),
        ]);
    }
}
