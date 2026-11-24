<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\InfrastructureLayerFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $key
 * @property int $order
 * @property string $color
 * @property string $name
 * @property-read Collection<int, InfrastructureService> $services
 */
class InfrastructureLayer extends Model
{
    /** @use HasFactory<InfrastructureLayerFactory> */
    use HasFactory;

    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['name'];

    /** @var list<string> */
    protected $fillable = ['key', 'order', 'color', 'name'];

    /**
     * @return HasMany<InfrastructureService, $this>
     */
    public function services(): HasMany
    {
        return $this->hasMany(InfrastructureService::class)->orderBy('order')->orderBy('id');
    }
}
