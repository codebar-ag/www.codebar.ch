<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reference extends Model
{
    protected $fillable = [
        'source_type',
        'source_id',
        'reference_type',
        'reference_id',
        'reference_locale',
    ];

    /**
     * @return MorphTo<Model, $this>
     */
    public function source(): MorphTo
    {
        return $this->morphTo('source');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function target(): MorphTo
    {
        return $this->morphTo('target', 'reference_type', 'reference_id');
    }
}
