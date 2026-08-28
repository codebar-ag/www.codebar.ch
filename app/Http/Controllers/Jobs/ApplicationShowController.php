<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\View\View;

class ApplicationShowController extends Controller
{
    public function __invoke(Application $application): View
    {
        return view('app.jobs.application')->with([
            'page' => (new PageAction(locale: null, routeName: 'jobs.internship.show'))->default(),
            'application' => $application,
            'files' => $application->files()->latest()->get(),
        ]);
    }
}
