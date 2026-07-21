<?php

namespace App\Models;

use App\Enums\AiModelCategoryEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiModel extends Model
{
    protected $casts = [
        'category' => AiModelCategoryEnum::class,
        'role' => 'json',
        'archived_at' => 'date',
    ];

    public function replacedBy(): BelongsTo
    {
        return $this->belongsTo(AiModel::class, 'replaced_by_id');
    }

    public function localizedRole(): ?string
    {
        return $this->role[substr(app()->getLocale(), 0, 2)] ?? null;
    }

    public function licenseLabel(): ?string
    {
        $key = $this->licenseKey();

        return $key ? __('components.ai_llm.licenses.'.$key.'.label') : $this->license;
    }

    public function licenseTooltip(): ?string
    {
        $key = $this->licenseKey();

        return $key ? __('components.ai_llm.licenses.'.$key.'.tooltip') : null;
    }

    private function licenseKey(): ?string
    {
        return match ($this->license) {
            'MIT' => 'mit',
            'Apache-2.0' => 'apache',
            'Gemma' => 'gemma',
            default => null,
        };
    }
}
