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

        $logs = $response->json();

        return $this->parseSpendLogs($date, is_array($logs) ? $logs : []);
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
     * @param  array<mixed>  $logs
     * @return Collection<int, array{date: string, model: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int, spend: float}>
     */
    private function parseSpendLogs(CarbonImmutable $date, array $logs): Collection
    {
        // The end_date bound is inclusive, so the response can contain rows of
        // the following day — keep only rows that started on the requested day.
        return collect($logs)
            ->filter(fn (mixed $log): bool => filled($this->stringValue($log, 'model_group')))
            ->filter(fn (mixed $log): bool => str_starts_with($this->stringValue($log, 'startTime'), $date->toDateString()))
            ->groupBy(fn (mixed $log): string => $this->stringValue($log, 'model_group'))
            ->map(fn (Collection $rows, string $model): array => [
                'date' => $date->toDateString(),
                'model' => $model,
                'prompt_tokens' => $rows->sum(fn (mixed $log): int => $this->intValue($log, 'prompt_tokens')),
                'completion_tokens' => $rows->sum(fn (mixed $log): int => $this->intValue($log, 'completion_tokens')),
                'total_tokens' => $rows->sum(fn (mixed $log): int => $this->intValue($log, 'total_tokens')),
                'requests' => $rows->count(),
                'spend' => round($rows->sum(fn (mixed $log): float => $this->floatValue($log, 'spend')), 6),
            ])
            ->values();
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
