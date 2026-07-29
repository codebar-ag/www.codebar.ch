<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\StoreLlmUsageAction;
use Database\Seeders\Concerns\ReadsCsv;
use Illuminate\Database\Seeder;

class AiModelDailyUsagesTableSeeder extends Seeder
{
    use ReadsCsv;

    /**
     * Seed local usage data from the current LLM usage export, so the
     * analytics page has real-shaped data to show locally.
     */
    public function run(): void
    {
        $rows = collect($this->readCsv('ai_model_daily_usages.csv'))->map(fn (array $row) => [
            'date' => $row['date'],
            'model' => $row['model'],
            'prompt_tokens' => (int) $row['prompt_tokens'],
            'completion_tokens' => (int) $row['completion_tokens'],
            'total_tokens' => (int) $row['total_tokens'],
            'requests' => (int) $row['requests'],
            'spend' => (float) $row['spend'],
        ]);

        (new StoreLlmUsageAction)->store($rows);
    }
}
