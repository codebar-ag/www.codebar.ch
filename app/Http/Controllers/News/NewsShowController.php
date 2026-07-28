<?php

namespace App\Http\Controllers\News;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Seo\SchemaNodes;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsShowController extends Controller
{
    public function __invoke(string $locale, News $news): View
    {
        // withReferences builds the alternate-locale PageDTOs, so the article
        // gets hreflang links pointing at its own translation rather than at
        // the news index.
        $page = (new PageAction($locale))->news(news: $news, withReferences: true);

        return view('app.news.show')->with([
            'page' => $page,
            'published_at' => $news->published_at?->format('d.m.Y'),
            'last_updated_at' => $news->updated_at?->format('d.m.Y'),
            'author' => $news->author,
            'title' => $news->title,
            'teaser' => $news->teaser,
            'tags' => collect($news->tags),
            'content' => Str::of($news->content ?? '')->markdown(),
            'schema' => SchemaNodes::blogPosting($news, $page, $locale),
        ]);
    }
}
