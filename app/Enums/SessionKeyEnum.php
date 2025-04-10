<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SessionKeyEnum: string implements HasLabel
{
    case LANGUAGE = 'language';

    public function getLabel(): string
    {
        return match ($this) {
            SessionKeyEnum::LANGUAGE => __('Language'),
        };
    }
}
