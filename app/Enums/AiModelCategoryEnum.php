<?php

namespace App\Enums;

enum AiModelCategoryEnum: string
{
    case REASONING_CODING = 'reasoning_coding';
    case VISION_DOCUMENTS = 'vision_documents';
    case RETRIEVAL_SEARCH = 'retrieval_search';

    public function title(string $locale): string
    {
        return __('components.ai_llm.categories.'.$this->value.'.title', locale: $locale);
    }

    public function description(string $locale): string
    {
        return __('components.ai_llm.categories.'.$this->value.'.description', locale: $locale);
    }
}
