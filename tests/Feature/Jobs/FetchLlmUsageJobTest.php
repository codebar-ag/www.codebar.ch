<?php

declare(strict_types=1);

use App\Actions\FetchLlmUsageAction;
use App\Actions\LlmUsageStatsAction;
use App\Actions\StoreLlmUsageAction;
use App\Jobs\FetchLlmUsageJob;
use App\Models\AiModelDailyUsage;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Spatie\ResponseCache\Facades\ResponseCache;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    config()->set('services.litellm.url', 'https://llm.codebar.net');
    config()->set('services.litellm.master_key', 'test-master-key');
});

/**
 * @return array<int, array<string, mixed>>
 */
function spendLogsPayload(int $promptTokens): array
{
    return [
        ['model_group' => 'qwen3.6:35b', 'prompt_tokens' => $promptTokens, 'completion_tokens' => 50, 'total_tokens' => $promptTokens + 50, 'spend' => 0.5, 'startTime' => '2026-07-20T10:00:00.000000Z'],
    ];
}

it('stores the fetched usage and updates existing rows idempotently', function () {
    Http::fake([
        'llm.codebar.net/spend/logs*' => Http::sequence()
            ->push(spendLogsPayload(promptTokens: 100))
            ->push(spendLogsPayload(promptTokens: 200)),
    ]);

    (new FetchLlmUsageJob(CarbonImmutable::parse('2026-07-20')))->handle(
        app(FetchLlmUsageAction::class),
        app(StoreLlmUsageAction::class),
    );

    expect(AiModelDailyUsage::count())->toBe(1);

    assertDatabaseHas('ai_model_daily_usages', [
        'model' => 'qwen3.6:35b',
        'prompt_tokens' => 100,
        'requests' => 1,
    ]);

    (new FetchLlmUsageJob(CarbonImmutable::parse('2026-07-20')))->handle(
        app(FetchLlmUsageAction::class),
        app(StoreLlmUsageAction::class),
    );

    expect(AiModelDailyUsage::count())->toBe(1);

    assertDatabaseHas('ai_model_daily_usages', [
        'model' => 'qwen3.6:35b',
        'prompt_tokens' => 200,
    ]);
})->group('llm-analytics');

it('invalidates the stats cache after storing', function () {
    Http::fake([
        'llm.codebar.net/spend/logs*' => Http::response(spendLogsPayload(promptTokens: 100)),
    ]);

    $versionBefore = Cache::get(LlmUsageStatsAction::VERSION_CACHE_KEY, 0);
    assert(is_int($versionBefore));

    (new FetchLlmUsageJob(CarbonImmutable::parse('2026-07-20')))->handle(
        app(FetchLlmUsageAction::class),
        app(StoreLlmUsageAction::class),
    );

    expect(Cache::get(LlmUsageStatsAction::VERSION_CACHE_KEY))->toBeGreaterThan($versionBefore);
})->group('llm-analytics');

it('does not clear the response cache when a single day is stored', function () {
    // Clearing the whole site's rendered HTML is the sync's decision, not one day's —
    // otherwise an hourly run wipes the response cache once per day in its window.
    ResponseCache::partialMock()->shouldReceive('clear')->never();

    (new StoreLlmUsageAction)->store(collect([[
        'date' => '2026-07-20',
        'model' => 'qwen3.6:35b',
        'prompt_tokens' => 100,
        'completion_tokens' => 50,
        'total_tokens' => 150,
        'requests' => 1,
        'spend' => 0.5,
    ]]));

})->group('llm-analytics');

it('clears the response cache once after the whole sync batch finishes', function () {
    Http::fake(['llm.codebar.net/spend/logs*' => Http::response(spendLogsPayload(promptTokens: 100))]);

    // Four days are synced below; the rendered pages are dropped exactly once.
    ResponseCache::partialMock()->shouldReceive('clear')->once();

    runArtisan('llm:fetch-analytics', ['--from' => '2026-07-20', '--to' => '2026-07-23'])
        ->assertSuccessful();

})->group('llm-analytics');
