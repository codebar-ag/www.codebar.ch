<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InfrastructureFlowEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $from
 * @property string $to
 * @property InfrastructureFlowEnum $flow
 * @property int $order
 */
class InfrastructureConnection extends Model
{
    public const string REQUEST = 'req';

    /** @var list<string> */
    protected $fillable = ['from', 'to', 'flow', 'order'];

    protected $attributes = [
        'flow' => InfrastructureFlowEnum::CONSTANT,
    ];

    protected $casts = [
        'flow' => InfrastructureFlowEnum::class,
    ];
}
