<?php

namespace App\Http\Controllers\Start;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StartIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        dd((new PageAction('start.index'))->default());

        return view('app.start.index')->with([
            'page' => (new PageAction('start.index'))->default(),
            'news' => (new ViewDataAction)->news($locale),
        ]);
    }
}
