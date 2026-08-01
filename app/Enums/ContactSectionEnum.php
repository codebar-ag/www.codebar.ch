<?php

declare(strict_types=1);

namespace App\Enums;

enum ContactSectionEnum: string
{
    case EMPLOYEES = 'employees';
    case COLLABORATIONS = 'collaborations';
    case BOARD_MEMBERS = 'board_members';
}
