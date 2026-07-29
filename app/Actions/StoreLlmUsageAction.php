<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\AiModel;
use App\Models\AiModelDailyUsage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class StoreLlmUsageAction
{
    /**
     * @param  Collection<int, array{date: string, model: string, prompt_tokens: int, completion_tokens: int, total_tokens: int, requests: int, spend: float}>  $rows
     */
    public function store(Collection $rows): int
    {
        if ($rows->isEmpty()) {
            return 0;
        }

        $aiModelIds = AiModel::pluck('id', 'name');

        $rows = $rows->map(fn (array $row) => [
            ...$row,
            'ai_model_id' => $aiModelIds->get($row['model']),
        ]);

        $count = AiModelDailyUsage::upsert(
            $rows->all(),
            uniqueBy: ['date', 'model'],
            update: ['ai_model_id', 'prompt_tokens', 'completion_tokens', 'total_tokens', 'requests', 'spend'],
        );

        // Bumping the version invalidates every cached stats query at once, which is
        // all this action is responsible for. Clearing the rendered pages is the
        // sync's job, not one day's — FetchLlmAnalyticsCommand does it once the whole
        // batch has finished, rather than four times per hourly run.
        Cache::increment(LlmUsageStatsAction::VERSION_CACHE_KEY);

        return $count;
    }
}
