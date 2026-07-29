<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionKeyEnum: string
{
    case LANGUAGE = 'language';

    public function getLabel(): string
    {
        return match ($this) {
            SessionKeyEnum::LANGUAGE => __('Language'),
        };
    }
}
