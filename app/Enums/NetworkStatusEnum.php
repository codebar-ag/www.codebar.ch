<?php

namespace App\Enums;

enum NetworkStatusEnum: string
{
    case ACTIVE = 'active';
    case ENDED = 'ended';

    public function getLabel(): string
    {
        return match ($this) {
            NetworkStatusEnum::ACTIVE => __('Active'),
            NetworkStatusEnum::ENDED => __('Ended'),
        };
    }
}
