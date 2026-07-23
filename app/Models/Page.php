<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['title', 'description'];

    public function getLocale(): string
    {
        return app()->getLocale();
    }
}
