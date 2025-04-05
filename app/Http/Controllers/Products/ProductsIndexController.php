<?php

namespace App\Http\Controllers\Products;

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
        return view('app.products.index')->with([
            'products' => (new ViewDataAction)->products(),
        ]);
    }
}
