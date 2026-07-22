<?php

namespace App\Enums;

enum AiModelCategoryEnum: string
{
    case REASONING_CODING = 'reasoning_coding';
    case VISION_DOCUMENTS = 'vision_documents';
    case RETRIEVAL_SEARCH = 'retrieval_search';

    public function title(): string
    {
        return __('components.ai_llm.categories.'.$this->value.'.title');
    }

    public function description(): string
    {
        return __('components.ai_llm.categories.'.$this->value.'.description');
    }
}
