<?php

declare(strict_types=1);

use App\Enums\ApplicationStatusEnum;
use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Database\QueryException;

it('casts status to the enum and submitted_at to a date', function () {
    $application = Application::factory()->submitted()->create();

    expect($application->status)->toBe(ApplicationStatusEnum::Submitted)
        ->and($application->isSubmitted())->toBeTrue()
        ->and($application->submitted_at)->not->toBeNull();
})->group('applications');

it('starts as a draft', function () {
    $application = Application::factory()->create();

    expect($application->status)->toBe(ApplicationStatusEnum::Draft)
        ->and($application->isSubmitted())->toBeFalse();
})->group('applications');

it('builds the full name from first and last name', function () {
    $application = Application::factory()->make(['first_name' => 'Mina', 'last_name' => 'Keller']);

    expect($application->name())->toBe('Mina Keller');
})->group('applications');

it('builds a partial name without empty gaps', function () {
    $application = Application::factory()->make(['first_name' => 'Mina', 'last_name' => null]);

    expect($application->name())->toBe('Mina');
})->group('applications');

it('has many files', function () {
    $application = Application::factory()->create();
    ApplicationFile::factory()->count(2)->create(['application_id' => $application->id]);

    expect($application->files)->toHaveCount(2)
        ->and($application->files->first())->toBeInstanceOf(ApplicationFile::class);
})->group('applications');

it('enforces one application per email and job', function () {
    Application::factory()->create(['email' => 'mina@example.com']);

    Application::factory()->create(['email' => 'mina@example.com']);
})->throws(QueryException::class)->group('applications');

it('formats the file size for humans', function () {
    $file = ApplicationFile::factory()->make(['size' => 1024 * 1024]);

    expect($file->humanSize())->toBe('1 MB');
})->group('applications');
