<?php

declare(strict_types=1);

namespace App\Enums;

enum InfrastructureOperationEnum: string
{
    case SELF = 'self';
    case MANAGED = 'managed';
    case SERVICE = 'service';

    public function getLabel(): string
    {
        return (string) __('components.infrastructure.operation_'.$this->value);
    }
}
