<?php

namespace App\Http\Controllers\Technologies;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TechnologiesIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        return view('app.technologies.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'technologies.index'))->default(),
            'technologies' => (new ViewDataAction)->technologies($locale),
        ]);
    }
}
