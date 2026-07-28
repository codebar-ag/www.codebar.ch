<?php

namespace App\Http\Controllers\OpenSource;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class OpenSourceIndexController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        return view('app.open-source.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'open-source.index'))->default(),
            'openSource' => (new ViewDataAction)->openSource($locale),
        ]);
    }
}
