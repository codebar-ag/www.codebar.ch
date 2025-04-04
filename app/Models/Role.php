<?php

namespace App\Models;

use App\Traits\HasActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use HasActivity;

    /** @phpstan-ignore-next-line */
    use HasFactory;
}
