<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InfrastructureOperationEnum;
use Database\Factories\InfrastructureServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $key
 * @property int $order
 * @property string $name
 * @property string $text
 * @property string $provider
 * @property string $vendor
 * @property string|null $vendor_country
 * @property string $place
 * @property list<string> $countries
 * @property InfrastructureOperationEnum $operation
 * @property list<array{type: string, url: string}> $links
 */
class InfrastructureService extends Model
{
    /** @use HasFactory<InfrastructureServiceFactory> */
    use HasFactory;

    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['name', 'text'];

    /** @var list<string> */
    protected $fillable = [
        'infrastructure_layer_id',
        'key',
        'order',
        'name',
        'text',
        'provider',
        'vendor',
        'vendor_country',
        'place',
        'countries',
        'operation',
        'links',
    ];

    protected $casts = [
        'countries' => 'array',
        'links' => 'array',
        'operation' => InfrastructureOperationEnum::class,
    ];

    /**
     * @return BelongsTo<InfrastructureLayer, $this>
     */
    public function layer(): BelongsTo
    {
        return $this->belongsTo(InfrastructureLayer::class, 'infrastructure_layer_id');
    }
}
