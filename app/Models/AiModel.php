<?php

namespace App\Models;

use App\Enums\AiModelCategoryEnum;
use App\Enums\AiModelLicenseEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiModel extends Model
{
    protected $casts = [
        'category' => AiModelCategoryEnum::class,
        'license' => AiModelLicenseEnum::class,
        'role' => 'json',
        'in_evaluation' => 'boolean',
        'archived_at' => 'date',
    ];

    public function replacedBy(): BelongsTo
    {
        return $this->belongsTo(AiModel::class, 'replaced_by_id');
    }

    public function localizedRole(string $locale): ?string
    {
        return $this->role[substr($locale, 0, 2)] ?? null;
    }
}
