<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AiModelDailyUsageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiModelDailyUsage extends Model
{
    /** @use HasFactory<AiModelDailyUsageFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'date',
        'model',
        'ai_model_id',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'requests',
        'spend',
    ];

    protected $casts = [
        'date' => 'date',
        'spend' => 'decimal:6',
    ];

    /**
     * @return BelongsTo<AiModel, $this>
     */
    public function aiModel(): BelongsTo
    {
        return $this->belongsTo(AiModel::class);
    }
}
