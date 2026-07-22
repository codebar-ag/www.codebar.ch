<?php

use App\Models\AiModel;
use App\Models\AiModelDailyUsage;
use Database\Seeders\AiModelDailyUsagesTableSeeder;
use Database\Seeders\AiModelsTableSeeder;

it('seeds demo usage rows linked to the active models', function () {
    $this->seed(AiModelsTableSeeder::class);
    $this->seed(AiModelDailyUsagesTableSeeder::class);

    $activeNames = AiModel::whereNull('archived_at')->pluck('name');

    expect(AiModelDailyUsage::count())->toBeGreaterThan(0)
        ->and(AiModelDailyUsage::distinct('model')->pluck('model')->diff($activeNames))->toBeEmpty()
        ->and(AiModelDailyUsage::whereNull('ai_model_id')->count())->toBe(0);
})->group('seeders', 'ai');

it('does nothing when no models exist', function () {
    $this->seed(AiModelDailyUsagesTableSeeder::class);

    expect(AiModelDailyUsage::count())->toBe(0);
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
