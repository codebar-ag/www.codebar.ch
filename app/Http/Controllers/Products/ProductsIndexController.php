<?php

namespace App\Http\Controllers\Products;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProductsIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        return view('app.products.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'products.index'))->default(),
            'products' => (new ViewDataAction)->products($locale),
        ]);
    }
}
