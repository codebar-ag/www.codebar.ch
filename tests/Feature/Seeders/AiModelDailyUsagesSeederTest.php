<?php

use App\Models\AiModel;
use App\Models\AiModelDailyUsage;
use Database\Seeders\AiModelDailyUsagesTableSeeder;
use Database\Seeders\AiModelsTableSeeder;

it('seeds usage rows from the export, linking rows whose model still exists', function () {
    $this->seed(AiModelsTableSeeder::class);
    $this->seed(AiModelDailyUsagesTableSeeder::class);

    $knownNames = AiModel::pluck('name');

    expect(AiModelDailyUsage::count())->toBeGreaterThan(0)
        ->and(AiModelDailyUsage::whereIn('model', $knownNames)->whereNull('ai_model_id')->count())->toBe(0)
        ->and(AiModelDailyUsage::whereNotIn('model', $knownNames)->count())->toBeGreaterThan(0);
})->group('seeders', 'ai');

it('seeds usage rows even when no models exist yet, leaving them unlinked', function () {
    $this->seed(AiModelDailyUsagesTableSeeder::class);

    expect(AiModelDailyUsage::count())->toBeGreaterThan(0)
        ->and(AiModelDailyUsage::whereNull('ai_model_id')->count())->toBe(AiModelDailyUsage::count());
})->group('seeders', 'ai');

it('upserts on re-run instead of duplicating rows', function () {
    $this->seed(AiModelsTableSeeder::class);
    $this->seed(AiModelDailyUsagesTableSeeder::class);

    $firstRun = AiModelDailyUsage::select(['date', 'model'])->get()
        ->map(fn (AiModelDailyUsage $row): string => $row->date->format('Y-m-d').'|'.$row->model);

    $this->seed(AiModelDailyUsagesTableSeeder::class);

    $secondRun = AiModelDailyUsage::select(['date', 'model'])->get()
        ->map(fn (AiModelDailyUsage $row): string => $row->date->format('Y-m-d').'|'.$row->model);

    expect($secondRun->duplicates())->toBeEmpty()
        ->and($secondRun->count())->toBeGreaterThanOrEqual($firstRun->count());
})->group('seeders', 'ai');
