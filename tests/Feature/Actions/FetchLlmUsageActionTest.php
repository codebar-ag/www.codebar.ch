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

it('fetches and aggregates spend logs per day and model', function () {
    Http::fake([
        'llm.codebar.net/spend/logs*' => Http::response([
            ['model_group' => 'qwen3.6:35b', 'prompt_tokens' => 100, 'completion_tokens' => 50, 'total_tokens' => 150, 'spend' => 0.5, 'startTime' => '2026-07-20T10:00:00.000000Z'],
            ['model_group' => 'qwen3.6:35b', 'prompt_tokens' => 10, 'completion_tokens' => 5, 'total_tokens' => 15, 'spend' => 0.25, 'startTime' => '2026-07-20T11:00:00.000000Z'],
            ['model_group' => 'qwen3-embedding:4b', 'prompt_tokens' => 870, 'completion_tokens' => 0, 'total_tokens' => 870, 'spend' => 0, 'startTime' => '2026-07-20T12:00:00.000000Z'],
            ['model_group' => '', 'prompt_tokens' => 5, 'completion_tokens' => 5, 'total_tokens' => 10, 'spend' => 0, 'startTime' => '2026-07-20T13:00:00.000000Z'],
            ['model_group' => 'qwen3.6:35b', 'prompt_tokens' => 99, 'completion_tokens' => 99, 'total_tokens' => 198, 'spend' => 1, 'startTime' => '2026-07-21T00:10:00.000000Z'],
        ]),
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
        return str_contains($request->url(), '/spend/logs')
            && $request->hasHeader('Authorization', 'Bearer test-master-key')
            && data_get($request->data(), 'start_date') === '2026-07-20'
            && data_get($request->data(), 'end_date') === '2026-07-21'
            && data_get($request->data(), 'summarize') === 'false';
    });
})->group('llm-analytics');

it('throws when the proxy responds with an error', function () {
    Http::fake([
        'llm.codebar.net/spend/logs*' => Http::response([], 500),
    ]);

    (new FetchLlmUsageAction)->fetchDay(CarbonImmutable::parse('2026-07-20'));
})->throws(RequestException::class)->group('llm-analytics');
