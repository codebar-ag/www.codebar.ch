<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\AiModelDailyUsage;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Closure;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
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
     * The calendar years usage has been recorded in, oldest first.
     *
     * @return Collection<int, string>
     */
    public function years(): Collection
    {
        return $this->remember('years', function () {
            return AiModelDailyUsage::query()
                ->selectRaw('DISTINCT EXTRACT(YEAR FROM date)::int AS year')
                ->orderBy('year')
                ->pluck('year')
                ->map(fn (mixed $year): string => $this->toYear($year))
                ->values();
        });
    }

    /**
     * When the sync last wrote usage rows to the database.
     */
    public function lastSyncedAt(): ?Carbon
    {
        return $this->remember('last_synced_at', function (): ?Carbon {
            return $this->toDate(AiModelDailyUsage::query()->max('updated_at'));
        });
    }

    private function toDate(mixed $value): ?Carbon
    {
        return match (true) {
            is_string($value) => Carbon::parse($value),
            $value instanceof DateTimeInterface => Carbon::instance($value),
            default => null,
        };
    }

    /**
     * @return Collection<int, array{label: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int}>
     */
    public function monthlyBreakdown(?string $year, ?string $month, ?string $model): Collection
    {
        $suffix = 'breakdown_'.($year ?? 'all').'_'.($month ?? 'all').'_'.($model ?? 'all');

        return $this->remember($suffix, function () use ($year, $month, $model) {
            // Grouped in the database rather than in PHP: this table grows by one row
            // per model per day forever, and the page only ever shows the totals.
            return $this->filtered($model)
                ->when($year, fn (Builder $query) => $query->whereYear('date', $year))
                ->when($month, fn (Builder $query) => $query->whereMonth('date', $month))
                ->selectRaw("to_char(date, 'YYYY-MM') AS label")
                ->selectRaw('COALESCE(SUM(prompt_tokens), 0)::bigint AS prompt_tokens')
                ->selectRaw('COALESCE(SUM(completion_tokens), 0)::bigint AS completion_tokens')
                ->selectRaw('COALESCE(SUM(total_tokens), 0)::bigint AS total_tokens')
                ->selectRaw('COALESCE(SUM(requests), 0)::bigint AS requests')
                ->groupByRaw("to_char(date, 'YYYY-MM')")
                ->orderByRaw("to_char(date, 'YYYY-MM')")
                ->get()
                ->map(fn (AiModelDailyUsage $row): array => [
                    'label' => $this->toString($row->getAttribute('label')),
                    'prompt_tokens' => $this->toInt($row->getAttribute('prompt_tokens')),
                    'completion_tokens' => $this->toInt($row->getAttribute('completion_tokens')),
                    'total_tokens' => $this->toInt($row->getAttribute('total_tokens')),
                    'requests' => $this->toInt($row->getAttribute('requests')),
                ])
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
            $row = $this->filtered($model)
                ->when($from, fn (Builder $query) => $query->where('date', '>=', $from))
                ->selectRaw('COALESCE(SUM(prompt_tokens), 0)::bigint AS prompt_tokens')
                ->selectRaw('COALESCE(SUM(completion_tokens), 0)::bigint AS completion_tokens')
                ->selectRaw('COALESCE(SUM(total_tokens), 0)::bigint AS total_tokens')
                ->selectRaw('COALESCE(SUM(requests), 0)::bigint AS requests')
                ->first();

            return [
                'prompt_tokens' => $this->toInt($row?->getAttribute('prompt_tokens')),
                'completion_tokens' => $this->toInt($row?->getAttribute('completion_tokens')),
                'total_tokens' => $this->toInt($row?->getAttribute('total_tokens')),
                'requests' => $this->toInt($row?->getAttribute('requests')),
            ];
        });
    }

    /**
     * Aggregate columns come back untyped — PostgreSQL hands SUM() over a bigint back as
     * a string, and a grouped row carries no cast from the model.
     */
    private function toInt(mixed $value): int
    {
        return is_numeric($value) ? (int) $value : 0;
    }

    private function toString(mixed $value): string
    {
        return is_string($value) ? $value : '';
    }

    /** The filter compares against the query string, so a year travels as text. */
    private function toYear(mixed $value): string
    {
        return (string) $this->toInt($value);
    }

    /**
     * The model filter every aggregate shares: a named model, everything that is not a
     * known model ("other"), or no filter at all.
     *
     * @return Builder<AiModelDailyUsage>
     */
    private function filtered(?string $model): Builder
    {
        return AiModelDailyUsage::query()
            ->when($model === self::OTHER_MODEL, fn (Builder $query) => $query->whereNull('ai_model_id'))
            ->when($model !== null && $model !== self::OTHER_MODEL, fn (Builder $query) => $query->where('model', $model));
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
