<?php

declare(strict_types=1);

namespace App\Enums;

enum NetworkCategoryEnum: string
{
    case COLLABORATION = 'collaboration';
    case SOFTWARE = 'software';
    case INFRASTRUCTURE = 'infrastructure';
    case SPONSORING = 'sponsoring';
    case CERTIFICATION = 'certification';

    public function getLabel(): string
    {
        return match ($this) {
            NetworkCategoryEnum::COLLABORATION => __('Collaboration Partner'),
            NetworkCategoryEnum::SOFTWARE => __('Software Partner'),
            NetworkCategoryEnum::INFRASTRUCTURE => __('Infrastructure Partner'),
            NetworkCategoryEnum::SPONSORING => __('Sponsoring & Community'),
            NetworkCategoryEnum::CERTIFICATION => __('Certifications & Memberships'),
        };
    }
}
