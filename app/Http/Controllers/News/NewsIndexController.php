<?php

namespace App\Http\Controllers\News;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class NewsIndexController extends Controller
{
    public function __invoke(ViewDataAction $data): View
    {
        $locale = app()->getLocale();

        return view('app.news.index')->with([
            'page' => PageAction::for('news.index', $locale),
            'news' => $data->news($locale),
        ]);
    }
}
