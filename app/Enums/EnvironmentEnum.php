<?php

namespace App\Enums;

use App\Traits\HasNovaEnumLabel;
use Filament\Support\Contracts\HasLabel;

enum EnvironmentEnum: string implements HasLabel
{
    use HasNovaEnumLabel;

    case LOCAL = 'local';
    case STAGING = 'staging';
    case PRODUCTION = 'production';

    public function getLabel(): string
    {
        return match ($this) {
            EnvironmentEnum::LOCAL => __('Local'),
            EnvironmentEnum::STAGING => __('Staging'),
            EnvironmentEnum::PRODUCTION => __('Production'),
        };
    }
}
