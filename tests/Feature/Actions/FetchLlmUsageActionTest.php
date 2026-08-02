<?php

declare(strict_types=1);

use App\Actions\FetchLlmUsageAction;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('services.litellm.url', 'https://llm.codebar.net');
    config()->set('services.litellm.master_key', 'test-master-key');
});

/**
 * @param  array<int, array<string, mixed>>  $data
 * @return array<string, mixed>
 */
function llmPage(array $data, int $page, int $totalPages): array
{
    return [
        'data' => $data,
        'total' => $totalPages * 100,
        'page' => $page,
        'page_size' => 100,
        'total_pages' => $totalPages,
    ];
}

it('fetches and aggregates spend logs per day and model', function () {
    Http::fake([
        'llm.codebar.net/spend/logs/v2*' => Http::response(llmPage([
            ['model_group' => 'qwen3.6:35b', 'prompt_tokens' => 100, 'completion_tokens' => 50, 'total_tokens' => 150, 'spend' => 0.5, 'startTime' => '2026-07-20T10:00:00.000000Z'],
            ['model_group' => 'qwen3.6:35b', 'prompt_tokens' => 10, 'completion_tokens' => 5, 'total_tokens' => 15, 'spend' => 0.25, 'startTime' => '2026-07-20T11:00:00.000000Z'],
            ['model_group' => 'qwen3-embedding:4b', 'prompt_tokens' => 870, 'completion_tokens' => 0, 'total_tokens' => 870, 'spend' => 0, 'startTime' => '2026-07-20T12:00:00.000000Z'],
            ['model_group' => '', 'prompt_tokens' => 5, 'completion_tokens' => 5, 'total_tokens' => 10, 'spend' => 0, 'startTime' => '2026-07-20T13:00:00.000000Z'],
            ['model_group' => 'qwen3.6:35b', 'prompt_tokens' => 99, 'completion_tokens' => 99, 'total_tokens' => 198, 'spend' => 1, 'startTime' => '2026-07-21T00:10:00.000000Z'],
        ], page: 1, totalPages: 1)),
    ]);

    $rows = (new FetchLlmUsageAction)->fetchDay(CarbonImmutable::parse('2026-07-20'));

    expect($rows)->toHaveCount(2);

    $qwen = $rows->firstOrFail(fn (array $row): bool => $row['model'] === 'qwen3.6:35b');

    expect($qwen['date'])->toBe('2026-07-20')
        ->and($qwen['prompt_tokens'])->toBe(110)
        ->and($qwen['completion_tokens'])->toBe(55)
        ->and($qwen['total_tokens'])->toBe(165)
        ->and($qwen['requests'])->toBe(2)
        ->and($qwen['spend'])->toBe(0.75);

    Http::assertSent(function (Request $request) {
        return str_contains($request->url(), '/spend/logs/v2')
            && $request->hasHeader('Authorization', 'Bearer test-master-key')
            && data_get($request->data(), 'start_date') === '2026-07-20 00:00:00'
            && data_get($request->data(), 'end_date') === '2026-07-20 23:59:59'
            && data_get($request->data(), 'page') === 1
            && data_get($request->data(), 'page_size') === 100;
    });
})->group('llm-analytics');

it('walks every page and aggregates across them without loading the whole day at once', function () {
    Http::fake([
        'llm.codebar.net/spend/logs/v2*' => function (Request $request) {
            $pageValue = data_get($request->data(), 'page');
            $page = is_numeric($pageValue) ? (int) $pageValue : 1;

            return match ($page) {
                1 => Http::response(llmPage([
                    ['model_group' => 'qwen3.6:35b', 'prompt_tokens' => 100, 'completion_tokens' => 50, 'total_tokens' => 150, 'spend' => 0.5, 'startTime' => '2026-07-20T10:00:00.000000Z'],
                ], page: 1, totalPages: 2)),
                2 => Http::response(llmPage([
                    ['model_group' => 'qwen3.6:35b', 'prompt_tokens' => 10, 'completion_tokens' => 5, 'total_tokens' => 15, 'spend' => 0.25, 'startTime' => '2026-07-20T11:00:00.000000Z'],
                ], page: 2, totalPages: 2)),
                default => Http::response(llmPage([], page: $page, totalPages: 2)),
            };
        },
    ]);

    $rows = (new FetchLlmUsageAction)->fetchDay(CarbonImmutable::parse('2026-07-20'));

    expect($rows)->toHaveCount(1);

    $qwen = $rows->firstOrFail();

    expect($qwen['prompt_tokens'])->toBe(110)
        ->and($qwen['requests'])->toBe(2);

    Http::assertSentCount(2);
})->group('llm-analytics');

it('throws when the proxy responds with an error', function () {
    Http::fake([
        'llm.codebar.net/spend/logs/v2*' => Http::response([], 500),
    ]);

    (new FetchLlmUsageAction)->fetchDay(CarbonImmutable::parse('2026-07-20'));
})->throws(RequestException::class)->group('llm-analytics');
