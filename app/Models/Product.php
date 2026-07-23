<?php

namespace App\Models;

use App\Traits\HasLocalizedRouteBinding;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    use HasLocalizedRouteBinding;
    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = [
        'name', 'headline', 'teaser', 'content',
        'features_heading', 'features_intro', 'features',
        'deployment_heading', 'deployment_intro', 'deployment_options',
        'cta_heading', 'cta_body',
    ];

    protected $casts = [
        'published' => 'boolean',
        'tags' => 'json',
    ];

    public function getLocale(): string
    {
        return app()->getLocale();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return HasMany<ProductModule, $this>
     */
    public function productModules(): HasMany
    {
        return $this->hasMany(ProductModule::class);
    }
}
