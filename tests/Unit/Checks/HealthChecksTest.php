<?php

use App\Checks\FailedJobsCheck;
use App\Checks\FilesystemsDefaultCheck;
use Illuminate\Support\Str;
use Spatie\Health\Enums\Status;

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
