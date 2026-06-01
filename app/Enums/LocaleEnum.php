<?php

namespace App\Enums;

enum LocaleEnum: string
{
    case DE = 'de_CH';
    case EN = 'en_CH';

    public function label(): string
    {
        return match ($this) {
            LocaleEnum::DE => 'Deutsch',
            LocaleEnum::EN => 'English',
        };
    }

    public function getLabel(): string
    {
        return $this->label();
    }
}
