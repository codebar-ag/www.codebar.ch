<?php

namespace App\Http\Controllers\Legal;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TermsIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        // @todo Notification
        /*        return view('app.legal.terms.index')->with([
                    'page' => (new PageAction(locale: null, routeName: 'legal.terms.index'))->default(),
                ]);*/
    }
}
