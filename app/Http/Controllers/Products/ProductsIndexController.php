<?php

declare(strict_types=1);

namespace App\Http\Controllers\Products;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductsIndexController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        $locale = app()->getLocale();

                return view('app.products.index')->with([
                    'page' => (new PageAction(locale: null, routeName: 'products.index'))->default(),
                    'products' => (new ViewDataAction)->products($locale),
                ]);*/
    }
}
