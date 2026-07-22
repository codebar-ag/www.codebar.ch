<?php

use App\Enums\AiModelCategoryEnum;
use App\Models\AiModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('create an AiModel model', function () {
    $model = AiModel::create([
        'category' => AiModelCategoryEnum::REASONING_CODING,
        'order' => 1,
        'name' => 'qwen3.6:35b',
        'license' => 'Apache-2.0',
        'role' => ['de' => 'Chat', 'en' => 'Chat'],
    ]);

    expect($model)->toBeInstanceOf(AiModel::class)
        ->and($model->category)->toBe(AiModelCategoryEnum::REASONING_CODING);
})->group('unit', 'models');

test('delete an AiModel model', function () {
    $model = AiModel::create([
        'category' => AiModelCategoryEnum::REASONING_CODING,
        'order' => 1,
        'name' => 'qwen3.6:35b',
    ]);

    $this->assertTrue($model->delete());
})->group('unit', 'models');

it('defines the replacedBy and dailyUsages relations', function () {
    $model = new AiModel;

    expect($model->replacedBy())->toBeInstanceOf(BelongsTo::class)
        ->and($model->dailyUsages())->toBeInstanceOf(HasMany::class);
})->group('unit', 'models');

it('returns the localized role for the current locale', function () {
    $model = new AiModel;
    $model->role = ['de' => 'Chat-Modell', 'en' => 'Chat model'];

    app()->setLocale('de_CH');
    expect($model->localizedRole())->toBe('Chat-Modell');

    app()->setLocale('en_CH');
    expect($model->localizedRole())->toBe('Chat model');
})->group('unit', 'models');

it('returns license label and tooltip for known licenses', function () {
    $model = new AiModel;
    $model->license = 'Apache-2.0';

    expect($model->licenseLabel())->toBe(__('components.ai_llm.licenses.apache.label'))
        ->and($model->licenseTooltip())->toBe(__('components.ai_llm.licenses.apache.tooltip'));
})->group('unit', 'models');

it('falls back to the raw license for unknown licenses', function () {
    $model = new AiModel;
    $model->license = 'Custom-License';

    expect($model->licenseLabel())->toBe('Custom-License')
        ->and($model->licenseTooltip())->toBeNull();
})->group('unit', 'models');
