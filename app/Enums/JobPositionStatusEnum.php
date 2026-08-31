<?php

declare(strict_types=1);

namespace App\Enums;

enum JobPositionStatusEnum: string
{
    case Open = 'open';

    case InProcess = 'in-process';
}
