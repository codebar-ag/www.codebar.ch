<?php

declare(strict_types=1);

use App\Enums\AiModelCategoryEnum;
use App\Models\AiModel;
use App\Models\AiModelDailyUsage;

use function Pest\Laravel\get;

beforeEach(function () {
    $qwen = AiModel::create([
        'category' => AiModelCategoryEnum::REASONING_CODING,
        'order' => 1,
        'name' => 'qwen3.6:35b',
    ]);

    $coder = AiModel::create([
        'category' => AiModelCategoryEnum::REASONING_CODING,
        'order' => 2,
        'name' => 'qwen3-coder:30b',
    ]);

    AiModelDailyUsage::factory()->create([
        'date' => '2026-05-10',
        'model' => 'qwen3.6:35b',
        'ai_model_id' => $qwen->id,
        'prompt_tokens' => 1000,
        'completion_tokens' => 500,
        'total_tokens' => 1500,
        'requests' => 10,
        'spend' => 123.456789,
    ]);

    AiModelDailyUsage::factory()->create([
        'date' => '2026-06-10',
        'model' => 'qwen3-coder:30b',
        'ai_model_id' => $coder->id,
        'prompt_tokens' => 2000,
        'completion_tokens' => 1000,
        'total_tokens' => 3000,
        'requests' => 20,
        'spend' => 9.87654,
    ]);
});

it('renders the analytics page with localized month labels', function (string $url, string $may, string $june) {
    get($url)
        ->assertOk()
        ->assertSee($may)
        ->assertSee($june)
        ->assertSee("1'500")
        ->assertSee("3'000");
})->with([
    ['/ki/llm-analytics', 'Mai 2026', 'Juni 2026'],
    ['/ai/llm-analytics', 'May 2026', 'June 2026'],
])->group('llm-analytics');

it('filters by month', function (string $month) {
    get('/ki/llm-analytics?month='.$month)
        ->assertOk()
        ->assertSee('Mai 2026')
        ->assertDontSee('Juni 2026');
})->with(['05', 'Mai'])->group('llm-analytics');

it('filters by year', function () {
    get('/ki/llm-analytics?year=2026')
        ->assertOk()
        ->assertSee('Mai 2026')
        ->assertSee('Juni 2026');
})->group('llm-analytics');

it('filters by model', function () {
    get('/ki/llm-analytics?model=qwen3.6:35b')
        ->assertOk()
        ->assertSee("1'500")
        ->assertDontSee('Juni 2026');
})->group('llm-analytics');

it('groups usage of models without a catalogue entry as other', function () {
    AiModelDailyUsage::factory()->create([
        'date' => '2026-04-10',
        'model' => 'gpt-oss:20b',
        'ai_model_id' => null,
        'prompt_tokens' => 7000,
        'completion_tokens' => 777,
        'total_tokens' => 7777,
        'requests' => 7,
    ]);

    get('/ki/llm-analytics?model=Andere')
        ->assertOk()
        ->assertSee('April 2026')
        ->assertSee("7'777")
        ->assertDontSee('Mai 2026')
        ->assertDontSee('gpt-oss:20b');
})->group('llm-analytics');

it('ignores unknown filter values', function () {
    get('/ki/llm-analytics?year=1999&month=13&model=unknown-model')
        ->assertOk()
        ->assertSee('Mai 2026')
        ->assertSee('Juni 2026');
})->group('llm-analytics');

it('paginates the periods', function () {
    collect(range(7, 12))->each(fn (int $month) => AiModelDailyUsage::factory()->create([
        'date' => sprintf('2026-%02d-01', $month),
        'model' => 'qwen3.6:35b',
        'total_tokens' => $month * 100,
    ]));

    get('/ki/llm-analytics')
        ->assertOk()
        ->assertSee('Dezember 2026')
        ->assertDontSee('Mai 2026');

    get('/ki/llm-analytics?page=2')
        ->assertOk()
        ->assertSee('Mai 2026')
        ->assertDontSee('Dezember 2026');
})->group('llm-analytics');

it('shows when the data was last synced', function () {
    AiModelDailyUsage::query()->update(['updated_at' => '2026-06-11 05:03:00']);

    get('/ki/llm-analytics')
        ->assertOk()
        ->assertSee('Zuletzt aktualisiert am 11.06.2026 05:03 Uhr.')
        ->assertDontSee('Daten bis und mit');
})->group('llm-analytics');

it('never displays spend', function () {
    get('/ki/llm-analytics')
        ->assertOk()
        ->assertDontSee('123.456789')
        ->assertDontSee('9.87654')
        ->assertDontSee('spend');
})->group('llm-analytics');
