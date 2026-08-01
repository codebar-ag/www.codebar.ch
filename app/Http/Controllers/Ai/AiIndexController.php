<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ai;

use App\Actions\LlmUsageStatsAction;
use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsTag;
use Illuminate\View\View;

class AiIndexController extends Controller
{
    /**
     * The topics this page collects its articles from. «KI» in the German files and
     * «AI» in the English ones become two separate tags on import, so both keys count.
     *
     * @var list<string>
     */
    private const array TOPIC_KEYS = ['ki', 'ai'];

    public function __invoke(LlmUsageStatsAction $stats, ViewDataAction $viewData): View
    {
        $locale = app()->getLocale();

        return view('app.ai.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'ai.index'))->default(),
            'llmSummary' => $stats->currentMonthSummary(),
            'hasUsage' => $stats->totalSummary()['requests'] > 0,
            // Every article on the topic, not a teaser of the newest few: this is the
            // page about it, and the news index stays one click away for the rest.
            'news' => $viewData->news($locale)
                ->filter(fn (News $entry): bool => $entry->newsTags->contains(
                    fn (NewsTag $tag): bool => in_array($tag->key, self::TOPIC_KEYS, true),
                ))
                ->values(),
        ]);
    }
}
