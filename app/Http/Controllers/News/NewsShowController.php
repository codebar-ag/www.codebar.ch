<?php

declare(strict_types=1);

namespace App\Http\Controllers\News;

use App\Actions\PageAction;
use App\DTO\ContactDTO;
use App\Http\Controllers\Controller;
use App\Markdown\NewsMarkdown;
use App\Models\News;
use App\Seo\SchemaNodes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class NewsShowController extends Controller
{
    private const int RELATED_ARTICLES = 3;

    private const int RELATED_CANDIDATE_POOL = 30;

    public function __invoke(string $locale, News $news, NewsMarkdown $markdown): View
    {
        // Drafts must not be reachable by guessing the URL — the route binding
        // resolves by slug alone and does not know about publication state.
        abort_if(! $news->isPublished(), 404);

        // withReferences builds the alternate-locale PageDTOs, so the article
        // gets hreflang links pointing at its own translation rather than at
        // the news index.
        $page = (new PageAction($locale))->news(news: $news, withReferences: true);

        $body = is_string($news->content) ? $news->content : '';
        $author = $news->authorContact;

        return view('app.news.show')->with([
            'page' => $page,
            'news' => $news,
            'title' => $news->title,
            'teaser' => $news->teaser,
            'tags' => collect($news->tags),
            'content' => $markdown->toHtml($body),
            'headings' => $markdown->headings($body),
            'authorName' => $news->authorName(),
            'authorRole' => $author !== null ? ContactDTO::fromModel($author, 'employees', $locale)->role : null,
            'authorImage' => $author?->image,
            'authorLinkedin' => $author !== null && is_array($author->icons) ? ($author->icons['linkedin'] ?? null) : null,
            'series' => $news->series,
            'seriesParts' => $news->seriesParts(),
            'related' => $this->relatedArticles($news),
            'schema' => SchemaNodes::blogPosting($news, $page, $locale),
        ]);
    }

    /**
     * Curated cross-links first, topped up with articles sharing a tag so the
     * footer is never empty on an article nobody has linked yet.
     *
     * @return Collection<int, News>
     */
    private function relatedArticles(News $news): Collection
    {
        $curated = $news->relatedArticles()->published()->get();

        if ($curated->count() >= self::RELATED_ARTICLES) {
            return $curated->take(self::RELATED_ARTICLES);
        }

        $tags = $this->tagStrings($news);

        $byTag = News::query()
            ->published()
            // Only the card fields — `content` holds the full article body in both
            // languages, and scoring by tag overlap never looks at it.
            ->select(['id', 'key', 'slug', 'title', 'teaser', 'hero_image', 'published_at', 'reading_minutes', 'tags', 'series_id', 'contact_id'])
            ->whereKeyNot($news->getKey())
            ->whereNotIn('id', $curated->modelKeys())
            ->when($news->series_id !== null, fn ($query) => $query->where(function ($inner) use ($news) {
                // Other parts of the same series already have their own navigation.
                $inner->whereNull('series_id')->orWhere('series_id', '!=', $news->series_id);
            }))
            ->orderByDesc('published_at')
            // Tag overlap is scored in PHP, so the candidate set has to be bounded:
            // "related" never reaches past the most recent articles anyway.
            ->limit(self::RELATED_CANDIDATE_POOL)
            ->get()
            ->sortByDesc(fn (News $candidate): int => count(array_intersect($tags, $this->tagStrings($candidate))))
            ->take(self::RELATED_ARTICLES - $curated->count());

        return $curated->merge($byTag)->values();
    }

    /**
     * @return array<int, string>
     */
    private function tagStrings(News $news): array
    {
        if (! is_array($news->tags)) {
            return [];
        }

        return array_values(array_filter($news->tags, fn (mixed $tag): bool => is_string($tag)));
    }
}
