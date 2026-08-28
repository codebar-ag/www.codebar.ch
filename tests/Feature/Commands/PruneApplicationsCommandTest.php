<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Support\Facades\Storage;

it('prunes stale drafts including their documents', function () {
    Storage::fake('s3');
    Storage::disk('s3')->put('applications/documents/stale.pdf', 'pdf');

    $stale = Application::factory()->create();
    ApplicationFile::factory()->create([
        'application_id' => $stale->id,
        'path' => 'applications/documents/stale.pdf',
    ]);
    $stale->timestamps = false;
    $stale->forceFill(['updated_at' => now()->subMonths(7)])->save();

    runArtisan('applications:prune')->assertSuccessful();

    expect(Application::query()->count())->toBe(0)
        ->and(ApplicationFile::query()->count())->toBe(0);

    Storage::disk('s3')->assertMissing('applications/documents/stale.pdf');
})->group('applications');

it('keeps fresh drafts and submitted applications', function () {
    Storage::fake('s3');

    Application::factory()->create();

    $submitted = Application::factory()->submitted()->create();
    $submitted->timestamps = false;
    $submitted->forceFill(['updated_at' => now()->subMonths(12)])->save();

    runArtisan('applications:prune')->assertSuccessful();

    expect(Application::query()->count())->toBe(2);
})->group('applications');
