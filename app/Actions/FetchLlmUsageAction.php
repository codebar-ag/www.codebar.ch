<?php

namespace App\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class FetchLlmUsageAction
{
    /**
     * Fetch the per-model usage aggregates for a single day from the LiteLLM proxy.
     *
     * @return Collection<int, array{date: string, model: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int, spend: float}>
     */
    public function fetchDay(CarbonImmutable $date): Collection
    {
        $response = $this->client()->get('/spend/logs', [
            'start_date' => $date->toDateString(),
            'end_date' => $date->addDay()->toDateString(),
            'summarize' => 'false',
        ])->throw();

        return $this->parseSpendLogs($date, $response->json() ?? []);
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl(config('services.litellm.url'))
            ->withToken(config('services.litellm.master_key'))
            ->acceptJson()
            ->connectTimeout(10)
            ->timeout(120)
            ->retry(times: 2, sleepMilliseconds: 1000, throw: false);
    }

    /**
     * @return Collection<int, array{date: string, model: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int, spend: float}>
     */
    private function parseSpendLogs(CarbonImmutable $date, array $logs): Collection
    {
        // The end_date bound is inclusive, so the response can contain rows of
        // the following day — keep only rows that started on the requested day.
        return collect($logs)
            ->filter(fn (mixed $log): bool => filled(data_get($log, 'model_group')))
            ->filter(fn (mixed $log): bool => str_starts_with((string) data_get($log, 'startTime'), $date->toDateString()))
            ->groupBy(fn (mixed $log): string => data_get($log, 'model_group'))
            ->map(fn (Collection $rows, string $model): array => [
                'date' => $date->toDateString(),
                'model' => $model,
                'prompt_tokens' => (int) $rows->sum(fn (mixed $log): int => (int) data_get($log, 'prompt_tokens', 0)),
                'completion_tokens' => (int) $rows->sum(fn (mixed $log): int => (int) data_get($log, 'completion_tokens', 0)),
                'total_tokens' => (int) $rows->sum(fn (mixed $log): int => (int) data_get($log, 'total_tokens', 0)),
                'requests' => $rows->count(),
                'spend' => round($rows->sum(fn (mixed $log): float => (float) data_get($log, 'spend', 0)), 6),
            ])
            ->values();
    }
}
