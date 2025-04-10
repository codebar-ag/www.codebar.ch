<?php

namespace App\Http\Controllers\Legal;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ImprintIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.legal.imprint.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'legal.imprint.index'))->default(),
        ]);
    }
}
