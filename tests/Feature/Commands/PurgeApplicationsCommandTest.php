<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

it('purges all applications including documents and notifications', function () {
    Storage::fake('s3');
    Storage::disk('s3')->put('applications/documents/cv.pdf', 'pdf');
    Storage::disk('s3')->put('applications/documents/orphan.pdf', 'pdf');
    Storage::disk('s3')->put('applications/exports/bewerbung-1-mira-muster.zip', 'zip');

    $application = Application::factory()->submitted()->create();
    ApplicationFile::factory()->create([
        'application_id' => $application->id,
        'path' => 'applications/documents/cv.pdf',
    ]);

    DB::table('notifications')->insert([
        'id' => (string) Str::uuid(),
        'type' => 'App\Notifications\ApplicationSubmittedNotification',
        'notifiable_type' => Application::class,
        'notifiable_id' => $application->id,
        'data' => '{}',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    runArtisan('applications:purge', ['--force' => true])->assertSuccessful();

    expect(Application::query()->count())->toBe(0)
        ->and(ApplicationFile::query()->count())->toBe(0)
        ->and(DB::table('notifications')->count())->toBe(0);

    Storage::disk('s3')->assertMissing('applications/documents/cv.pdf');
    Storage::disk('s3')->assertMissing('applications/documents/orphan.pdf');
    Storage::disk('s3')->assertMissing('applications/exports/bewerbung-1-mira-muster.zip');
})->group('applications');

it('aborts without confirmation', function () {
    Application::factory()->create();

    runArtisan('applications:purge')
        ->expectsConfirmation('This permanently deletes ALL applications, uploaded documents, related notifications and export zips. Continue?', 'no')
        ->assertSuccessful();

    expect(Application::query()->count())->toBe(1);
})->group('applications');
