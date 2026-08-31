<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CacheKeyEnum;
use App\Enums\JobPositionStatusEnum;
use Database\Factories\JobPositionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class JobPosition extends Model
{
    /** @use HasFactory<JobPositionFactory> */
    use HasFactory;

    use HasTranslations;

    /** @var array<int, string> */
    protected array $translatable = ['title', 'teaser'];

    /** @var list<string> */
    protected $fillable = [
        'key',
        'published',
        'sort',
        'status',
        'route_name',
        'title',
        'teaser',
    ];

    protected $casts = [
        'published' => 'boolean',
        'status' => JobPositionStatusEnum::class,
    ];

    protected static function booted(): void
    {
        static::saved(fn () => self::clearPublishedCache());
        static::deleted(fn () => self::clearPublishedCache());
    }

    public static function clearPublishedCache(): void
    {
        foreach (CacheKeyEnum::JOB_POSITIONS_PUBLISHED->forAllLocales() as $key) {
            Cache::forget($key);
        }
    }

    /**
     * @return HasMany<Application, $this>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_key', 'key');
    }

    public function isOpen(): bool
    {
        return $this->status === JobPositionStatusEnum::Open;
    }

    public function isInProcess(): bool
    {
        return $this->status === JobPositionStatusEnum::InProcess;
    }
}
