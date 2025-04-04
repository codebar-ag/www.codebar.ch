<?php

namespace App\Enums;

use App\Traits\HasNovaEnumLabel;
use Filament\Support\Contracts\HasLabel;

enum SessionKeyEnum: string implements HasLabel
{
    use HasNovaEnumLabel;

    case LANGUAGE = 'language';

    public function getLabel(): string
    {
        return match ($this) {
            SessionKeyEnum::LANGUAGE => __('Language'),
        };
    }
}
