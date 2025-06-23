<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Configuration extends Model
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = [
        'contact',
        'terms',
        'imprint',
        'privacy',
        'links',
        'footer',
    ];

    protected $casts = [
        'section_services' => 'boolean',
        'section_products' => 'boolean',
        'section_technologies' => 'boolean',
        'section_open_source' => 'boolean',

        'contact' => 'json',
        'terms' => 'json',
        'imprint' => 'json',
        'privacy' => 'json',
        'links' => 'json',
        'footer' => 'json',
    ];
}
