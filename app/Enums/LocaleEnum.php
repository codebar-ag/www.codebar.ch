<?php

declare(strict_types=1);

namespace App\Enums;

enum LocaleEnum: string
{
    case DE = 'de_CH';
    case EN = 'en_CH';

    public function getLabel(): string
    {
        return match ($this) {
            LocaleEnum::DE => __('DE'),
            LocaleEnum::EN => __('EN'),
        };
    }
}
