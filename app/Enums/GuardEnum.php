<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum GuardEnum: string implements HasLabel
{
    case WEB = 'web';

    public function getLabel(): string
    {
        return match ($this) {
            GuardEnum::WEB => __('Web'),
        };
    }
}
