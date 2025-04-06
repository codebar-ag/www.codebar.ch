<?php

namespace App\View\Components;

use App\Actions\ViewDataAction;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function __construct(protected mixed $page) {}

    public function render(): View
    {
        $locale = app()->getLocale();

        return view('layouts.app')->with([
            'locale' => Str::slug($locale),
            'page' => $this->page,
            'services' => (new ViewDataAction)->services($locale),
            'products' => (new ViewDataAction)->products($locale),
        ]);
    }
}
