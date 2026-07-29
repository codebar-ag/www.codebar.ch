<?php

declare(strict_types=1);

namespace App\Enums;

enum GuardEnum: string
{
    case WEB = 'web';

    public function getLabel(): string
    {
        return match ($this) {
            GuardEnum::WEB => __('Web'),
        };
    }
}
