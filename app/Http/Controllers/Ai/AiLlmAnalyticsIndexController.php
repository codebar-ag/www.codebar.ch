<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ai;

use App\Actions\LlmUsageStatsAction;
use App\Actions\PageAction;
use App\DTO\LlmUsageFilterDTO;
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
        $monthOptions = $this->monthOptions();
        $hasOtherModels = $stats->hasOtherModels();

        $otherLabel = __('components.ai_llm_analytics.filter.other_models');
        $otherLabel = is_string($otherLabel) ? $otherLabel : LlmUsageStatsAction::OTHER_MODEL;

        $filter = LlmUsageFilterDTO::resolve($request, $years, $monthOptions, $models, $otherLabel, $hasOtherModels);

        $breakdown = $stats->monthlyBreakdown($filter->year, $filter->month, $filter->model)->reverse()->values();

        return view('app.ai.llm.analytics')->with([
            'page' => (new PageAction(locale: null, routeName: 'ai.llm.analytics.index'))->default(),
            'monthSummary' => $stats->currentMonthSummary($filter->model),
            'yearSummary' => $stats->currentYearSummary($filter->model),
            'totalSummary' => $stats->totalSummary($filter->model),
            'periods' => $this->paginate($breakdown, $filter),
            'grandTotal' => $this->grandTotal($breakdown),
            'modelOptions' => $hasOtherModels ? $models->concat([$otherLabel]) : $models,
            'years' => $years,
            'monthOptions' => $monthOptions,
            'year' => $filter->year,
            'month' => $filter->month,
            'model' => $filter->model,
            'modelLabel' => $filter->modelLabel($otherLabel),
            'lastSyncedAt' => $stats->lastSyncedAt(),
        ]);
    }

    /**
     * @return Collection<string, string>
     */
    private function monthOptions(): Collection
    {
        return collect(range(1, 12))->mapWithKeys(fn (int $month): array => [
            str_pad((string) $month, 2, '0', STR_PAD_LEFT) => HelperDate::monthName($month),
        ]);
    }

    /**
     * The breakdown is already one row per month, so it is paginated in memory rather
     * than re-queried per page.
     *
     * @param  Collection<int, array{label: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int}>  $breakdown
     * @return LengthAwarePaginator<int, array{label: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int}>
     */
    private function paginate(Collection $breakdown, LlmUsageFilterDTO $filter): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            items: $breakdown->forPage($page, self::PER_PAGE)->values(),
            total: $breakdown->count(),
            perPage: self::PER_PAGE,
            currentPage: $page,
            options: [
                'path' => Paginator::resolveCurrentPath(),
                'query' => $filter->toQuery(),
            ],
        );
    }

    /**
     * @param  Collection<int, array{label: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int}>  $breakdown
     * @return array<string, int>
     */
    private function grandTotal(Collection $breakdown): array
    {
        return [
            'prompt_tokens' => $breakdown->sum(fn (array $row): int => $row['prompt_tokens']),
            'completion_tokens' => $breakdown->sum(fn (array $row): int => $row['completion_tokens']),
            'total_tokens' => $breakdown->sum(fn (array $row): int => $row['total_tokens']),
            'requests' => $breakdown->sum(fn (array $row): int => $row['requests']),
        ];
    }
}
