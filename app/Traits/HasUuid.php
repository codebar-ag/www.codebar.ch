<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

trait HasUuid
{
    use HasUuids;

    /**
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public static function findByUuid(string $uuid): ?self
    {
        return self::where('uuid', $uuid)->first();
    }

    public static function findByUuidOrFail(string $uuid): self
    {
        return self::where('uuid', $uuid)->sole();
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeWithUuid(Builder $query, string $uuid): Builder
    {
        return $query->where('uuid', $uuid);
    }

    /**
     * @param  Builder<static>  $query
     * @param  array<int, string>  $uuids
     * @return Builder<static>
     */
    public function scopeWithUuids(Builder $query, array $uuids): Builder
    {
        return $query->whereIn('uuid', $uuids);
    }
}
