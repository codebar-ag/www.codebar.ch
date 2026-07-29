<?php

declare(strict_types=1);

use App\Models\AiModel;
use App\Models\AiModelDailyUsage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('create an AiModelDailyUsage model', function () {
    $model = AiModelDailyUsage::factory()->create();

    expect($model)->toBeInstanceOf(AiModelDailyUsage::class)
        ->and($model->total_tokens)->toBe($model->prompt_tokens + $model->completion_tokens);
})->group('unit', 'models');

test('delete an AiModelDailyUsage model', function () {
    $model = AiModelDailyUsage::factory()->create();

    expect($model->delete())->toBeTrue();
})->group('unit', 'models');

it('belongs to an AiModel', function () {
    $aiModel = AiModel::create([
        'category' => 'reasoning_coding',
        'order' => 1,
        'name' => 'qwen3.6:35b',
    ]);

    $usage = AiModelDailyUsage::factory()->create(['ai_model_id' => $aiModel->id]);

    expect($usage->aiModel())->toBeInstanceOf(BelongsTo::class)
        ->and($usage->aiModel?->is($aiModel))->toBeTrue();
})->group('unit', 'models');
