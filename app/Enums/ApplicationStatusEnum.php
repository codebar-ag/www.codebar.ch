<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationStatusEnum: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
}
