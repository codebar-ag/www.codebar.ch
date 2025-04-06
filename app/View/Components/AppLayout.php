<?php

namespace App\View\Components;

use App\Actions\ViewDataAction;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $locale = app()->getLocale();

        return view('layouts.app')->with([
            'services' => (new ViewDataAction)->services($locale),
            'products' => (new ViewDataAction)->products($locale),
        ]);
    }
}
