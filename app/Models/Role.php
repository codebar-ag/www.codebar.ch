<?php

namespace App\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    /** @use HasFactory<RoleFactory> */
    use HasFactory;
}
