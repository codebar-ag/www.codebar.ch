<?php

namespace App\Http\Controllers\OpenSource;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class OpenSourceIndexController extends Controller
{
    public function __invoke(ViewDataAction $data): View
    {
        $locale = app()->getLocale();

        return view('app.open-source.index')->with([
            'page' => PageAction::for('open-source.index', $locale),
            'openSource' => $data->openSource($locale),
        ]);
    }
}
