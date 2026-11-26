<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\InfrastructureStackFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $slug
 * @property bool $is_default
 * @property string $name
 * @property string $domain
 * @property string|null $image
 * @property string|null $sketch
 * @property list<string> $services
 * @property array<string, array<string, mixed>> $overrides
 */
class InfrastructureStack extends Model
{
    /** @use HasFactory<InfrastructureStackFactory> */
    use HasFactory;

    public const string SLUG = '/^[a-z0-9][a-z0-9.-]{1,60}$/';

    /** @var list<string> */
    protected $fillable = ['slug', 'is_default', 'name', 'domain', 'image', 'sketch', 'services', 'overrides'];

    protected $casts = [
        'is_default' => 'boolean',
        'services' => 'array',
        'overrides' => 'array',
    ];

    public static function default(): ?self
    {
        return self::query()->where('is_default', true)->oldest('id')->first();
    }

    public static function findBySlug(string $slug): ?self
    {
        if (preg_match(self::SLUG, $slug) !== 1) {
            return null;
        }

        return self::query()->where('slug', $slug)->first();
    }

    public function cover(): ?string
    {
        if ($this->sketch !== null && $this->sketch !== '') {
            return 'data:image/svg+xml;base64,'.base64_encode($this->sketch);
        }

        return $this->image !== null && $this->image !== '' ? asset($this->image) : null;
    }
}
