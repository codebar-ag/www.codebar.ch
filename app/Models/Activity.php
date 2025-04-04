<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    /** @phpstan-ignore-next-line */
    use HasFactory;

    public const array RELATIONS = [
        'subject',
        'causer',
    ];

    protected $guarded = [];

    protected $table = 'activity_log';

    protected $casts = [
        'id' => 'integer',
        'subject_id' => 'integer',
        'causer_id' => 'integer',
        'properties' => 'array',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }
}
