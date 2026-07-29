<?php

declare(strict_types=1);

use App\Enums\AiModelCategoryEnum;

it('returns correct cases array', function () {
    $expectedCases = [
        AiModelCategoryEnum::REASONING_CODING,
        AiModelCategoryEnum::VISION_DOCUMENTS,
        AiModelCategoryEnum::RETRIEVAL_SEARCH,
    ];

    expect(AiModelCategoryEnum::cases())->toBe($expectedCases);
})->group('enums', 'ai-model-category-enum');

it('returns a translated title and description per category', function () {
    foreach (AiModelCategoryEnum::cases() as $category) {
        expect($category->title())->toBe(__('components.ai_llm.categories.'.$category->value.'.title'))
            ->and($category->description())->toBe(__('components.ai_llm.categories.'.$category->value.'.description'));
    }
})->group('enums', 'ai-model-category-enum');
