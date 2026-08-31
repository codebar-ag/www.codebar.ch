<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ApplicationStatusEnum;
use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Application extends Model
{
    /** @use HasFactory<ApplicationFactory> */
    use HasFactory;

    use Notifiable;

    public const string JOB_KEY_INTERNSHIP = 'praktikum-ims';

    public const string EXPORTS_DIRECTORY = 'applications/exports';

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

    /**
     * The position lives as an imported content row that an import may delete and
     * recreate, so the link goes over the stable key rather than a database id.
     *
     * @return BelongsTo<JobPosition, $this>
     */
    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class, 'job_key', 'key');
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

    public function deleteExportsFromDisk(): void
    {
        foreach (Storage::disk('s3')->files(self::EXPORTS_DIRECTORY) as $path) {
            if (str_starts_with(basename($path), "bewerbung-{$this->id}-")) {
                Storage::disk('s3')->delete($path);
            }
        }
    }

    public function name(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
    }
}
