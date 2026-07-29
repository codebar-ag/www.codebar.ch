<?php

declare(strict_types=1);

use App\Checks\FailedJobsCheck;
use App\Checks\FilesystemsDefaultCheck;
use App\Checks\JobsCheck;
use Illuminate\Support\Str;
use Spatie\Health\Enums\Status;

function queueJob(int $availableAt): void
{
    DB::table('jobs')->insert([
        'queue' => 'default',
        'payload' => '{}',
        'attempts' => 0,
        'reserved_at' => null,
        'available_at' => $availableAt,
        'created_at' => $availableAt,
    ]);
}

it('passes when there are no failed jobs', function () {
    $result = (new FailedJobsCheck)->run();

    expect($result->status)->toBe(Status::ok());
})->group('unit', 'checks');

it('fails when there are failed jobs', function () {
    DB::table('failed_jobs')->insert([
        'uuid' => (string) Str::uuid(),
        'connection' => 'sync',
        'queue' => 'default',
        'payload' => '{}',
        'exception' => 'boom',
        'failed_at' => now(),
    ]);

    $result = (new FailedJobsCheck)->run();

    expect($result->status)->toBe(Status::failed());
})->group('unit', 'checks');

it('fails the filesystems default check when the default disk equals the fallback disk', function () {
    config(['filesystems.default' => 'local', 'filesystems.default_fallback' => 'local']);

    $result = (new FilesystemsDefaultCheck)->run();

    expect($result->status)->toBe(Status::failed());
})->group('unit', 'checks');

it('passes the filesystems default check when the default disk differs from the fallback disk', function () {
    config(['filesystems.default' => 'local', 'filesystems.default_fallback' => 's3']);

    $result = (new FilesystemsDefaultCheck)->run();

    expect($result->status)->toBe(Status::ok());
})->group('unit', 'checks');

it('passes the jobs check when the queue is empty', function () {
    expect((new JobsCheck)->run()->status)->toBe(Status::ok());
})->group('unit', 'checks');

it('passes the jobs check when jobs are queued but still fresh', function () {
    queueJob(now()->subMinute()->getTimestamp());

    expect((new JobsCheck)->run()->status)->toBe(Status::ok());
})->group('unit', 'checks');

it('fails the jobs check when the oldest job has been waiting past the threshold', function () {
    queueJob(now()->subMinutes(JobsCheck::STALLED_AFTER_MINUTES + 1)->getTimestamp());

    expect((new JobsCheck)->run()->status)->toBe(Status::failed());
})->group('unit', 'checks');

it('reports the pending job count rather than a boolean', function () {
    queueJob(now()->getTimestamp());
    queueJob(now()->getTimestamp());

    expect((new JobsCheck)->run()->shortSummary)->toBe('pending jobs: 2');
})->group('unit', 'checks');
