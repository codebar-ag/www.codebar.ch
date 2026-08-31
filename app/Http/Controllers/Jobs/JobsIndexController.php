<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use App\Models\JobPosition;
use Illuminate\View\View;

class JobsIndexController extends Controller
{
    public function __invoke(ViewDataAction $viewData): View
    {
        $positions = $viewData->jobPositions(app()->getLocale());

        return view('app.jobs.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'jobs.index'))->default(),
            'openPositions' => $positions->filter(fn (JobPosition $position): bool => $position->isOpen())->values(),
            'inProcessPositions' => $positions->filter(fn (JobPosition $position): bool => $position->isInProcess())->values(),
        ]);
    }
}
