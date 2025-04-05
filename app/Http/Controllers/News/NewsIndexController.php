<?php

namespace App\Http\Controllers\News;

use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class NewsIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.news.index')->with([
            'news' => (new ViewDataAction)->news(),
        ]);
    }
}
