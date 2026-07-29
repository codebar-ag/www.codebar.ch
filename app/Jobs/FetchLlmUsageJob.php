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
