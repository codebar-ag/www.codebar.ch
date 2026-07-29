<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LocaleEnum;
use App\Traits\HasLocalizedRouteBinding;
use Database\Factories\ProductModuleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductModule extends Model
{
    /** @use HasFactory<ProductModuleFactory> */
    use HasFactory;

    use HasLocalizedRouteBinding;

    /** @var list<string> */
    protected $fillable = [
        'published',
        'locale',
        'slug',
        'tags',
        'product_id',
    ];

    protected $casts = [
        'published' => 'boolean',
        'locale' => LocaleEnum::class,
        'tags' => 'json',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
