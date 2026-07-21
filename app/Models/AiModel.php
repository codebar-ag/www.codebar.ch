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

    public function localizedRole(string $locale): ?string
    {
        return $this->role[substr($locale, 0, 2)] ?? null;
    }

    public function licenseLabel(string $locale): ?string
    {
        return $this->license ? __('components.ai_llm.licenses.'.$this->licenseKey().'.label', locale: $locale) : null;
    }

    public function licenseTooltip(string $locale): ?string
    {
        return $this->license ? __('components.ai_llm.licenses.'.$this->licenseKey().'.tooltip', locale: $locale) : null;
    }

    private function licenseKey(): string
    {
        return match ($this->license) {
            'MIT' => 'mit',
            'Apache-2.0' => 'apache',
            'Gemma' => 'gemma',
        };
    }
}
