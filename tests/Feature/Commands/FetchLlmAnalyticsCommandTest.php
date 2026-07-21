<?php

use App\Jobs\FetchLlmUsageJob;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\Queue;

it('dispatches one job per day for the default range', function () {
    Queue::fake();

    $this->artisan('llm:fetch-analytics')->assertSuccessful();

    Queue::assertPushed(FetchLlmUsageJob::class, 4);
})->group('llm-analytics');

it('backfills from the initial sync start with the full flag', function () {
    Queue::fake();

    $this->artisan('llm:fetch-analytics', ['--full' => true])->assertSuccessful();

    $expectedDays = (int) CarbonImmutable::parse('2026-01-01')->diffInDays(CarbonImmutable::today()) + 1;

    Queue::assertPushed(FetchLlmUsageJob::class, $expectedDays);

    Queue::assertPushed(fn (FetchLlmUsageJob $job) => $job->date->toDateString() === '2026-01-01');
})->group('llm-analytics');

it('dispatches one job per day for a custom range', function () {
    Queue::fake();

    $this->artisan('llm:fetch-analytics', ['--from' => '2026-01-01', '--to' => '2026-01-05'])
        ->assertSuccessful();

    Queue::assertPushed(FetchLlmUsageJob::class, 5);

    Queue::assertPushed(fn (FetchLlmUsageJob $job) => $job->date->toDateString() === '2026-01-01');
    Queue::assertPushed(fn (FetchLlmUsageJob $job) => $job->date->toDateString() === '2026-01-05');
})->group('llm-analytics');

it('throws on an invalid date', function () {
    Queue::fake();

    $this->artisan('llm:fetch-analytics', ['--from' => 'not-a-date']);
})->throws(InvalidFormatException::class)->group('llm-analytics');

it('fails when from is after to', function () {
    Queue::fake();

    $this->artisan('llm:fetch-analytics', ['--from' => '2026-02-01', '--to' => '2026-01-01'])
        ->assertFailed();

    Queue::assertNothingPushed();
})->group('llm-analytics');
