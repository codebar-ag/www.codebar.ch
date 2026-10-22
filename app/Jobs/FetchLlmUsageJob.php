<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\FetchLlmUsageAction;
use App\Actions\StoreLlmUsageAction;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchLlmUsageJob implements ShouldQueue
{
    use Batchable;
    use Queueable;

    public int $tries = 3;

    // The worker's default --timeout (60s) is shorter than a slow LiteLLM page can take,
    // so the worker was killed mid-job and the reserved job re-run until it hit
    // MaxAttemptsExceededException. This must stay below the database queue's
    // retry_after (90s), otherwise a still-running job is handed out a second time.
    public int $timeout = 85;

    public function __construct(public CarbonImmutable $date) {}

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [60, 300];
    }

    public function handle(FetchLlmUsageAction $fetch, StoreLlmUsageAction $store): void
    {
        $store->store($fetch->fetchDay($this->date));
    }
}
