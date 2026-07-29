<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\AiModel;
use App\Models\AiModelDailyUsage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Spatie\ResponseCache\Commands\ClearCommand;

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

        Cache::increment(LlmUsageStatsAction::VERSION_CACHE_KEY);

        Artisan::call(ClearCommand::class);

        return $count;
    }
}
