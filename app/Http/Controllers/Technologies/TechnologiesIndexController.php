<?php

namespace App\Http\Controllers\Technologies;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TechnologiesIndexController extends Controller
{
    public function __invoke(ViewDataAction $data): View
    {
        $locale = app()->getLocale();

        return view('app.technologies.index')->with([
            'page' => PageAction::for('technologies.index', $locale),
            'technologies' => $data->technologies($locale),
        ]);
    }
}
