<?php

namespace App\Enums;

use App\Traits\HasNovaEnumLabel;
use Filament\Support\Contracts\HasLabel;

enum GuardEnum: string implements HasLabel
{
    use HasNovaEnumLabel;

    case WEB = 'web';

    public function getLabel(): string
    {
        return match ($this) {
            GuardEnum::WEB => __('Web'),
        };
    }
}
