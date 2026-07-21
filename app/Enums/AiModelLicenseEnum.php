<?php

namespace App\Enums;

enum AiModelLicenseEnum: string
{
    case MIT = 'MIT';
    case APACHE_2_0 = 'Apache-2.0';
    case GEMMA = 'Gemma';

    public function label(string $locale): string
    {
        return __('components.ai_llm.licenses.'.$this->key().'.label', locale: $locale);
    }

    public function tooltip(string $locale): string
    {
        return __('components.ai_llm.licenses.'.$this->key().'.tooltip', locale: $locale);
    }

    private function key(): string
    {
        return match ($this) {
            self::MIT => 'mit',
            self::APACHE_2_0 => 'apache',
            self::GEMMA => 'gemma',
        };
    }
}
