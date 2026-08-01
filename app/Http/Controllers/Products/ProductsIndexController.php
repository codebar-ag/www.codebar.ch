<?php

declare(strict_types=1);

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ProductsIndexController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');
    }
}
