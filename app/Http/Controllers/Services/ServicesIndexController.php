<?php

namespace App\Http\Controllers\Services;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ServicesIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        return view('app.services.index')->with([
            'page' => (new PageAction('services.index'))->default(),
            'services' => (new ViewDataAction)->services($locale)->groupBy('group'),
        ]);
    }
}
