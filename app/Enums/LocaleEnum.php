<?php

namespace App\Enums;

use App\Traits\HasNovaEnumLabel;
use Filament\Support\Contracts\HasLabel;

enum LocaleEnum: string implements HasLabel
{
    use HasNovaEnumLabel;

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
