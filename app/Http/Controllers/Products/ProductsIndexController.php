<?php

namespace App\Http\Controllers\Products;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProductsIndexController extends Controller
{
    public function __invoke(ViewDataAction $data): View
    {
        $locale = app()->getLocale();

        return view('app.products.index')->with([
            'page' => PageAction::for('products.index', $locale),
            'products' => $data->products($locale),
        ]);
    }
}
