<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\View\View;

class NewsShowController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(News $news): View
    {
        return view('app.news.show')->with([
            'news' => $news,
        ]);
    }
}
