<?php

namespace App\Http\Controllers\News;

use App\Actions\PageAction;
use App\Content\MarkdownContentService;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class NewsShowController extends Controller
{
    public function __invoke(MarkdownContentService $content, string $locale, string $news): View
    {
        $localeEnum = LocaleEnum::from($locale);
        $item = $content->find('news', $localeEnum, $news) ?? abort(404);

        return view('app.news.show')->with([
            'page' => PageAction::fromContent($item),
            'published_at' => $item->publishedAt?->format('d.m.Y'),
            'last_updated_at' => $item->publishedAt?->format('d.m.Y'),
            'author' => $item->frontmatter['author'] ?? null,
            'title' => $item->title,
            'teaser' => $item->teaser,
            'tags' => $item->tags(),
            'content' => $item->body,
        ]);
    }
}
