<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Configuration extends Model
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = ['footer'];

    protected $casts = [
        'footer' => 'json',
    ];
}
