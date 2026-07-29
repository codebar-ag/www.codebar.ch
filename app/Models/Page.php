<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'key',
        'robots',
        'title',
        'description',
        'image',
    ];

    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['title', 'description'];

    public function getLocale(): string
    {
        return app()->getLocale();
    }
}
