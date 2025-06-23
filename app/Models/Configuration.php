<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    public array $translatable = [];

    protected $casts = [
        'section_services' => 'boolean',
        'section_products' => 'boolean',
        'section_technologies' => 'boolean',
        'section_open_source' => 'boolean',
        'links' => 'json',
        'footer' => 'json',
    ];
}
