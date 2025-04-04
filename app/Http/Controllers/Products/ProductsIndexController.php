<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProductsIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.products.index');
    }
}
