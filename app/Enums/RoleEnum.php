<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case ADMINISTRATOR = 'administrator';
    case USER = 'user';
    case API = 'api';

    public function getLabel(): string
    {
        return match ($this) {
            RoleEnum::ADMINISTRATOR => __('enums.role.administrator'),
            RoleEnum::USER => __('enums.role.user'),
            RoleEnum::API => __('enums.role.api'),
        };
    }

    public function route(): string
    {
        return 'role:'.$this->value;
    }
}
