<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AiModelCategoryEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property array<string, string>|null $role
 */
class AiModel extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'category',
        'order',
        'name',
        'provider',
        'ram',
        'license',
        'role',
        'link_label',
        'link_url',
        'archived_at',
        'replaced_by_id',
    ];

    protected $casts = [
        'category' => AiModelCategoryEnum::class,
        'role' => 'json',
        'archived_at' => 'date',
    ];

    /**
     * @return BelongsTo<AiModel, $this>
     */
    public function replacedBy(): BelongsTo
    {
        return $this->belongsTo(AiModel::class, 'replaced_by_id');
    }

    /**
     * @return HasMany<AiModelDailyUsage, $this>
     */
    public function dailyUsages(): HasMany
    {
        return $this->hasMany(AiModelDailyUsage::class);
    }

    public function localizedRole(): ?string
    {
        return $this->role[substr(app()->getLocale(), 0, 2)] ?? null;
    }

    public function licenseLabel(): ?string
    {
        $key = $this->licenseKey();

        if ($key === null) {
            return $this->license;
        }

        return __('components.ai_llm.licenses.'.$key.'.label');
    }

    public function licenseTooltip(): ?string
    {
        $key = $this->licenseKey();

        if ($key === null) {
            return null;
        }

        return __('components.ai_llm.licenses.'.$key.'.tooltip');
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
