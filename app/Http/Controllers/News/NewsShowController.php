<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsShowController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(News $news): View
    {
        return view('app.news.show')->with([
            'published_at' => $news->published_at?->format('d.m.Y'),
            'last_updated_at' => $news->updated_at?->format('d.m.Y'),
            'author' => $news->author,
            'title' => $news->title,
            'teaser' => $news->teaser,
            'content' => Str::of($news->content)->markdown(),
        ]);
    }
}
