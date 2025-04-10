<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum LocaleEnum: string implements HasLabel
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
