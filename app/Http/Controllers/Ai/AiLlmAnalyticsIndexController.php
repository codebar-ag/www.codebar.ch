<?php

namespace App\Http\Controllers\Ai;

use App\Actions\LlmUsageStatsAction;
use App\Actions\PageAction;
use App\Helpers\Facades\HelperDate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AiLlmAnalyticsIndexController extends Controller
{
    private const int PER_PAGE = 6;

    public function __invoke(Request $request, LlmUsageStatsAction $stats): View
    {
        $models = $stats->models();
        $years = $stats->years();
        $monthOptions = collect(range(1, 12))->mapWithKeys(fn (int $month) => [
            str_pad((string) $month, 2, '0', STR_PAD_LEFT) => HelperDate::monthName($month),
        ]);

        $otherLabel = __('components.ai_llm_analytics.filter.other_models');
        $modelOptions = $stats->hasOtherModels() ? $models->concat([$otherLabel]) : $models;

        $year = $years->first(fn (string $option) => $option === $request->query('year'));
        $month = $this->resolveMonth($monthOptions, (string) $request->query('month'));
        $model = $this->resolveModel($models, $otherLabel, (string) $request->query('model'), $stats->hasOtherModels());

        $breakdown = $stats->monthlyBreakdown($year, $month, $model)->reverse()->values();

        $page = Paginator::resolveCurrentPage();

        $periods = new LengthAwarePaginator(
            items: $breakdown->forPage($page, self::PER_PAGE)->values(),
            total: $breakdown->count(),
            perPage: self::PER_PAGE,
            currentPage: $page,
            options: [
                'path' => Paginator::resolveCurrentPath(),
                'query' => array_filter(['year' => $year, 'month' => $month, 'model' => $model]),
            ],
        );

        return view('app.ai.llm.analytics')->with([
            'page' => (new PageAction(locale: null, routeName: 'ai.llm.analytics.index'))->default(),
            'monthSummary' => $stats->currentMonthSummary($model),
            'yearSummary' => $stats->currentYearSummary($model),
            'totalSummary' => $stats->totalSummary($model),
            'periods' => $periods,
            'grandTotal' => [
                'prompt_tokens' => $breakdown->sum(fn (array $row): int => $row['prompt_tokens']),
                'completion_tokens' => $breakdown->sum(fn (array $row): int => $row['completion_tokens']),
                'total_tokens' => $breakdown->sum(fn (array $row): int => $row['total_tokens']),
                'requests' => $breakdown->sum(fn (array $row): int => $row['requests']),
            ],
            'modelOptions' => $modelOptions,
            'years' => $years,
            'monthOptions' => $monthOptions,
            'year' => $year,
            'month' => $month,
            'model' => $model,
            'modelLabel' => $model === LlmUsageStatsAction::OTHER_MODEL ? $otherLabel : $model,
            'lastSyncedAt' => $stats->lastSyncedAt(),
        ]);
    }

    /**
     * @param  Collection<int, string>  $models
     */
    private function resolveModel(Collection $models, string $otherLabel, string $input, bool $hasOther): ?string
    {
        if ($hasOther && (strcasecmp($input, $otherLabel) === 0 || strcasecmp($input, LlmUsageStatsAction::OTHER_MODEL) === 0)) {
            return LlmUsageStatsAction::OTHER_MODEL;
        }

        return $models->first(fn (string $option) => strcasecmp($option, $input) === 0);
    }

    /**
     * @param  Collection<string, string>  $monthOptions
     */
    private function resolveMonth(Collection $monthOptions, string $input): ?string
    {
        if ($monthOptions->has($input)) {
            return $input;
        }

        $key = $monthOptions->search(fn (string $label) => strcasecmp($label, $input) === 0);

        return $key === false ? null : $key;
    }
}
