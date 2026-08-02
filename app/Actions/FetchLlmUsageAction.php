<?php

declare(strict_types=1);

namespace App\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class FetchLlmUsageAction
{
    // The unpaginated /spend/logs endpoint is deprecated and can return an
    // unbounded response body — a busy day previously exhausted PHP's memory
    // limit decoding a single giant JSON payload. /spend/logs/v2 caps each
    // page at 100 rows, so we walk it page by page and aggregate as we go.
    private const int PAGE_SIZE = 100;

    /**
     * Fetch the per-model usage aggregates for a single day from the LiteLLM proxy.
     *
     * @return Collection<int, array{date: string, model: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int, spend: float}>
     */
    public function fetchDay(CarbonImmutable $date): Collection
    {
        $start = $date->startOfDay();
        $end = $date->endOfDay();

        $totals = [];
        $page = 1;
        $totalPages = 1;

        do {
            $body = $this->client()->get('/spend/logs/v2', [
                'start_date' => $start->toDateTimeString(),
                'end_date' => $end->toDateTimeString(),
                'page' => $page,
                'page_size' => self::PAGE_SIZE,
            ])->throw()->json();

            $rows = data_get($body, 'data');

            $this->accumulate($totals, $date, is_array($rows) ? $rows : []);

            $totalPages = max(1, $this->intValue($body, 'total_pages'));
            $page++;
        } while ($page <= $totalPages);

        return collect($totals)->values();
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl(config()->string('services.litellm.url'))
            ->withToken(config()->string('services.litellm.master_key'))
            ->acceptJson()
            ->connectTimeout(10)
            ->timeout(120)
            ->retry(times: 2, sleepMilliseconds: 1000, throw: false);
    }

    /**
     * @param  array<string, array{date: string, model: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int, spend: float}>  $totals
     * @param  array<mixed>  $rows
     */
    private function accumulate(array &$totals, CarbonImmutable $date, array $rows): void
    {
        foreach ($rows as $log) {
            $model = $this->stringValue($log, 'model_group');

            // Defensive: only aggregate rows that actually started on the requested day.
            if ($model === '' || ! str_starts_with($this->stringValue($log, 'startTime'), $date->toDateString())) {
                continue;
            }

            $totals[$model] ??= [
                'date' => $date->toDateString(),
                'model' => $model,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0,
                'requests' => 0,
                'spend' => 0.0,
            ];

            $totals[$model]['prompt_tokens'] += $this->intValue($log, 'prompt_tokens');
            $totals[$model]['completion_tokens'] += $this->intValue($log, 'completion_tokens');
            $totals[$model]['total_tokens'] += $this->intValue($log, 'total_tokens');
            $totals[$model]['requests']++;
            $totals[$model]['spend'] = round($totals[$model]['spend'] + $this->floatValue($log, 'spend'), 6);
        }
    }

    private function stringValue(mixed $log, string $key): string
    {
        $value = data_get($log, $key);

        return is_string($value) ? $value : '';
    }

    private function intValue(mixed $log, string $key): int
    {
        $value = data_get($log, $key);

        return is_numeric($value) ? (int) $value : 0;
    }

    private function floatValue(mixed $log, string $key): float
    {
        $value = data_get($log, $key);

        return is_numeric($value) ? (float) $value : 0.0;
    }
}
