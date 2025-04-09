<?php

namespace App\Http\Controllers\Jobs;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class JobsIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.jobs.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'jobs.index'))->default(),
        ]);
    }
}
