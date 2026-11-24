<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $from
 * @property string $to
 * @property int $order
 */
class InfrastructureConnection extends Model
{
    public const string REQUEST = 'req';

    /** @var list<string> */
    protected $fillable = ['from', 'to', 'order'];
}
