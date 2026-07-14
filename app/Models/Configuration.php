<?php

namespace App\Models;

use Database\Factories\ConfigurationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    /** @use HasFactory<ConfigurationFactory> */
    use HasFactory;

    protected $casts = [
        'component_intro' => 'json',
        'section_news' => 'boolean',
        'section_services' => 'boolean',
        'section_products' => 'boolean',
        'section_technologies' => 'boolean',
        'section_open_source' => 'boolean',
        'links' => 'json',
    ];
}
