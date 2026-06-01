<?php

namespace App\Enums;

enum EnvironmentEnum: string
{
    case LOCAL = 'local';
    case STAGING = 'staging';
    case PRODUCTION = 'production';
}
