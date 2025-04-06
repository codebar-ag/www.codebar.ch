<?php

namespace App\Http\Controllers\Start;

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

        return view('app.start.index')->with([
            'news' => (new ViewDataAction)->news($locale),
        ]);
    }
}
