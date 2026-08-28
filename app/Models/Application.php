<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ApplicationStatusEnum;
use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Application extends Model
{
    /** @use HasFactory<ApplicationFactory> */
    use HasFactory;

    use Notifiable;

    public const string JOB_KEY_INTERNSHIP = 'praktikum-ims';

    /** @var list<string> */
    protected $fillable = [
        'job_key',
        'email',
        'first_name',
        'last_name',
        'age',
        'city',
        'interests',
        'focus_fit',
        'built_so_far',
        'about',
        'github',
        'linkedin',
        'project_link',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'age' => 'integer',
        'status' => ApplicationStatusEnum::class,
        'submitted_at' => 'datetime',
    ];

    /**
     * @return HasMany<ApplicationFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(ApplicationFile::class);
    }

    public function isSubmitted(): bool
    {
        return $this->status === ApplicationStatusEnum::Submitted;
    }

    public function markdownHtml(?string $markdown): ?string
    {
        return blank($markdown) ? null : Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    public function name(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
    }
}
