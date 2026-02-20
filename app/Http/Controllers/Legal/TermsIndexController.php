<?php

namespace App\Http\Controllers\Legal;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TermsIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.legal.terms.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'legal.terms.index'))->default(),
        ]);
    }
}
