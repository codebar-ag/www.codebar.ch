<?php

declare(strict_types=1);

namespace App\Enums;

enum InfrastructureFlowEnum: string
{
    case CONSTANT = 'constant';
    case FREQUENT = 'frequent';
    case OCCASIONAL = 'occasional';
    case SCHEDULED = 'scheduled';
}
