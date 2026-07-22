<?php

namespace App\Actions;

use App\Models\AiModelDailyUsage;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LlmUsageStatsAction
{
    public const string VERSION_CACHE_KEY = 'llm_usage_version';

    public const string OTHER_MODEL = 'other';

    /**
     * @return Collection<int, string>
     */
    public function models(): Collection
    {
        return $this->remember('models', function () {
            return AiModelDailyUsage::query()
                ->whereNotNull('ai_model_id')
                ->distinct()
                ->orderBy('model')
                ->get(['model'])
                ->map(fn (AiModelDailyUsage $row): string => $row->model);
        });
    }

    public function hasOtherModels(): bool
    {
        return $this->remember('has_other_models', function () {
            return AiModelDailyUsage::query()->whereNull('ai_model_id')->exists();
        });
    }

    /**
     * @return Collection<int, numeric-string>
     */
    public function years(): Collection
    {
        return $this->remember('years', function () {
            return AiModelDailyUsage::query()
                ->orderBy('date')
                ->get(['date'])
                ->map(fn (AiModelDailyUsage $row): string => $row->date->format('Y'))
                ->unique()
                ->values();
        });
    }

    /**
     * @return Collection<int, array{label: string, prompt_tokens: int<0, max>, completion_tokens: int<0, max>, total_tokens: int<0, max>, requests: int<0, max>}>
     */
    public function monthlyBreakdown(?string $year, ?string $month, ?string $model): Collection
    {
        $suffix = 'breakdown_'.($year ?? 'all').'_'.($month ?? 'all').'_'.($model ?? 'all');

        return $this->remember($suffix, function () use ($year, $month, $model) {
            return AiModelDailyUsage::query()
                ->when($model === self::OTHER_MODEL, fn (Builder $query) => $query->whereNull('ai_model_id'))
                ->when($model && $model !== self::OTHER_MODEL, fn (Builder $query) => $query->where('model', $model))
                ->when($year, fn (Builder $query) => $query->whereYear('date', $year))
                ->when($month, fn (Builder $query) => $query->whereMonth('date', $month))
                ->orderBy('date')
                ->get()
                ->groupBy(fn (AiModelDailyUsage $row) => $row->date->format('Y-m'))
                ->map(
                    /** @param EloquentCollection<int, AiModelDailyUsage> $rows */
                    fn (EloquentCollection $rows, string $label) => [
                        'label' => $label,
                        'prompt_tokens' => $rows->sum(fn (AiModelDailyUsage $row): int => $row->prompt_tokens),
                        'completion_tokens' => $rows->sum(fn (AiModelDailyUsage $row): int => $row->completion_tokens),
                        'total_tokens' => $rows->sum(fn (AiModelDailyUsage $row): int => $row->total_tokens),
                        'requests' => $rows->sum(fn (AiModelDailyUsage $row): int => $row->requests),
                    ]
                )
                ->values();
        });
    }

    /**
     * @return array{prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int}
     */
    public function currentMonthSummary(?string $model = null): array
    {
        return $this->summary('month_summary', CarbonImmutable::now()->startOfMonth(), $model);
    }

    /**
     * @return array{prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int}
     */
    public function currentYearSummary(?string $model = null): array
    {
        return $this->summary('year_summary', CarbonImmutable::now()->startOfYear(), $model);
    }

    /**
     * @return array{prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int}
     */
    public function totalSummary(?string $model = null): array
    {
        return $this->summary('total_summary', null, $model);
    }

    /**
     * @return array{prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int}
     */
    private function summary(string $suffix, ?CarbonImmutable $from, ?string $model = null): array
    {
        return $this->remember($suffix.'_'.($model ?? 'all'), function () use ($from, $model) {
            $rows = AiModelDailyUsage::query()
                ->when($from, fn (Builder $query) => $query->where('date', '>=', $from))
                ->when($model === self::OTHER_MODEL, fn (Builder $query) => $query->whereNull('ai_model_id'))
                ->when($model && $model !== self::OTHER_MODEL, fn (Builder $query) => $query->where('model', $model))
                ->get();

            return [
                'prompt_tokens' => $rows->sum(fn (AiModelDailyUsage $row): int => $row->prompt_tokens),
                'completion_tokens' => $rows->sum(fn (AiModelDailyUsage $row): int => $row->completion_tokens),
                'total_tokens' => $rows->sum(fn (AiModelDailyUsage $row): int => $row->total_tokens),
                'requests' => $rows->sum(fn (AiModelDailyUsage $row): int => $row->requests),
            ];
        });
    }

    /**
     * @template TValue
     *
     * @param  Closure(): TValue  $callback
     * @return TValue
     */
    private function remember(string $suffix, Closure $callback): mixed
    {
        $version = Cache::get(self::VERSION_CACHE_KEY, 0);
        $version = is_int($version) ? $version : 0;
        $key = Str::slug("llm_usage_{$version}_{$suffix}", '_');

        return Cache::remember($key, now()->addHour(), $callback);
    }
}
