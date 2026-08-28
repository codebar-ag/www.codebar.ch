<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Application;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class StoreApplicationDocumentsAction
{
    /**
     * @param  array<int, UploadedFile>  $documents
     */
    public function __invoke(Application $application, array $documents): void
    {
        foreach ($documents as $document) {
            $uuid = (string) Str::uuid();

            $path = $document->storeAs(
                'applications/documents',
                $uuid.'.pdf',
                's3',
            );

            abort_if($path === false, 500, 'Failed to store the uploaded document.');

            $application->files()->create([
                'uuid' => $uuid,
                'disk' => 's3',
                'path' => $path,
                'original_name' => $document->getClientOriginalName(),
                'mime' => $document->getMimeType() ?? 'application/pdf',
                'size' => $document->getSize(),
            ]);
        }
    }
}
