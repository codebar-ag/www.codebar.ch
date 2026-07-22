<?php

namespace App\Http\Controllers\Services;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServicesIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        $locale = app()->getLocale();

                return view('app.services.index')->with([
                    'page' => (new PageAction(locale: null, routeName: 'services.index'))->default(),
                    'services' => (new ViewDataAction)->services($locale)->groupBy('group'),
                ]);*/
    }
}
