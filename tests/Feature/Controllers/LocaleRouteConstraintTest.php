<?php

declare(strict_types=1);

use App\Models\News;

use function Pest\Laravel\get;

/**
 * The {locale} segment used to be a free string. PageAction turns it into a route name
 * ("de-ch.news.show"), so anything that is not a real locale produced a route-not-found
 * exception — a 500 for every malformed link, truncated URL and scanner probe.
 */
beforeEach(function () {
    News::factory()->create([
        'published' => true,
        'published_at' => now()->subDay(),
        'slug' => ['de_CH' => 'test-artikel', 'en_CH' => 'test-article'],
    ]);
});

it('404s on a locale segment that is not a configured locale', function (string $url) {
    get($url)->assertNotFound();
})->with([
    'nonsense' => ['/aktuelles/not-a-locale/test-artikel'],
    'short form' => ['/aktuelles/de/test-artikel'],
    'path traversal shape' => ['/aktuelles/..%2F..%2Fetc/test-artikel'],
    'english route' => ['/news/not-a-locale/test-article'],
])->group('routes');

it('still resolves an article under a real locale', function () {
    get('/aktuelles/de_CH/test-artikel')->assertOk();
})->group('routes');
