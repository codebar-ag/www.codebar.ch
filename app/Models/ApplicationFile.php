<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ApplicationFileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

class ApplicationFile extends Model
{
    /** @use HasFactory<ApplicationFileFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'application_id',
        'uuid',
        'disk',
        'path',
        'original_name',
        'mime',
        'size',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function temporaryUrl(): string
    {
        return Storage::disk($this->disk)->temporaryUrl($this->path, now()->addDays(7));
    }

    public function humanSize(): string
    {
        return Number::fileSize($this->size);
    }

    public function deleteFromDisk(): void
    {
        Storage::disk($this->disk)->delete($this->path);
    }
}
