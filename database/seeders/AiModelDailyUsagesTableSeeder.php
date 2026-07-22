<?php

namespace Database\Seeders;

use App\Actions\StoreLlmUsageAction;
use App\Models\AiModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AiModelDailyUsagesTableSeeder extends Seeder
{
    private const int DAYS = 180;

    /**
     * Seed demo usage data for the active models, mirroring the shape of the
     * LiteLLM import so the analytics page has something to show locally.
     */
    public function run(): void
    {
        $models = AiModel::whereNull('archived_at')->pluck('name');

        if ($models->isEmpty()) {
            return;
        }

        $rows = [];

        foreach ($models as $model) {
            $isRetrieval = str_contains($model, 'embedding') || str_contains($model, 'reranker');
            $baseRequests = random_int(20, 400);

            for ($i = self::DAYS; $i >= 1; $i--) {
                $date = Carbon::today()->subDays($i);

                // Not every model is used every day - weekends are mostly quiet.
                if (random_int(1, 100) <= ($date->isWeekend() ? 70 : 15)) {
                    continue;
                }

                $requests = random_int((int) ($baseRequests * 0.4), $baseRequests * 2);
                $promptTokens = $requests * random_int(300, 4_000);
                $completionTokens = $isRetrieval
                    ? $requests * random_int(1, 10)
                    : $requests * random_int(100, 1_500);

                $rows[] = [
                    'date' => $date->format('Y-m-d'),
                    'model' => (string) $model,
                    'prompt_tokens' => $promptTokens,
                    'completion_tokens' => $completionTokens,
                    'total_tokens' => $promptTokens + $completionTokens,
                    'requests' => $requests,
                    'spend' => round(($promptTokens + $completionTokens) / 1_000_000 * 0.35, 6),
                ];
            }
        }

        (new StoreLlmUsageAction)->store(collect($rows));
    }
}
