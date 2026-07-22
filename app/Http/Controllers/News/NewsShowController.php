<?php

namespace App\Http\Controllers\News;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsShowController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(string $locale, News $news): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        return view('app.news.show')->with([
                    'page' => (new PageAction($locale))->news(news: $news),
                    'published_at' => $news->published_at?->format('d.m.Y'),
                    'last_updated_at' => $news->updated_at?->format('d.m.Y'),
                    'author' => $news->author,
                    'title' => $news->title,
                    'teaser' => $news->teaser,
                    'tags' => collect($news->tags),
                    'content' => Str::of($news->content ?? '')->markdown(),
                ]);*/
    }
}
