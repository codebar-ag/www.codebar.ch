<?php

namespace App\Http\Controllers\AboutUs;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AboutUsIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        $locale = app()->getLocale();

                return view('app.about-us.index')->with([
                    'page' => (new PageAction(locale: null, routeName: 'about-us.index'))->default(),
                    'contacts' => (new ViewDataAction)->contacts($locale),
                ]);*/
    }
}
