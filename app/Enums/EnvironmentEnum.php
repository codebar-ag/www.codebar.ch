<?php

namespace App\Enums;

enum EnvironmentEnum: string
{
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
