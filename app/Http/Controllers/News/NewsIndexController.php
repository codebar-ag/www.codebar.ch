<?php

namespace App\Http\Controllers\News;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\DTO\PageDTO;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsTag;
use App\Seo\SchemaNodes;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class NewsIndexController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        $page = (new PageAction(locale: null, routeName: 'news.index'))->default();
        $all = (new ViewDataAction)->news($locale);

        // Filtering happens by topic slug in the query string rather than on its own
        // route: the canonical URL stays /aktuelles, so filtered views never compete
        // with the index in search results.
        $topics = $this->topics($all, $locale);
        $active = $this->activeTopic($topics);

        $news = $active === null
            ? $all
            : $all->filter(fn (News $entry): bool => $entry->newsTags->contains('id', $active->id))->values();

        // The lead article is the one flagged in its front matter, otherwise the newest.
        $lead = $news->firstWhere('featured', true) ?? $news->first();
        $rest = $lead === null ? $news : $news->reject(fn (News $entry): bool => $entry->is($lead));

        return view('app.news.index')->with([
            'page' => $page,
            'lead' => $lead,
            'news' => $rest->values(),
            'topics' => $topics,
            'activeTopic' => $active,
            'total' => $news->count(),
            'schema' => $page instanceof PageDTO
                ? SchemaNodes::blog($all, $page, $locale)
                : [],
        ]);
    }

    /**
     * Topics that actually have a published article behind them, in the order they
     * first appear. A filter that leads to an empty page is worse than no filter.
     *
     * @param  Collection<int, News>  $news
     * @return Collection<int, NewsTag>
     */
    private function topics(Collection $news, string $locale): Collection
    {
        $used = $news->flatMap(fn (News $entry): array => $entry->newsTags->modelKeys())->unique();

        if ($used->isEmpty()) {
            return new Collection;
        }

        /** @var Collection<int, NewsTag> */
        return NewsTag::query()
            ->whereIn('id', $used->all())
            ->get()
            ->sortBy(function (NewsTag $tag) use ($locale): string {
                $title = $tag->getTranslation('title', $locale);

                return is_string($title) ? $title : '';
            })
            ->values();
    }

    /**
     * @param  Collection<int, NewsTag>  $topics
     */
    private function activeTopic(Collection $topics): ?NewsTag
    {
        $slug = request()->query('thema');

        if (! is_string($slug) || $slug === '') {
            return null;
        }

        return $topics->first(fn (NewsTag $tag): bool => $tag->slug === $slug);
    }
}
