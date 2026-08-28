<?php

declare(strict_types=1);

use App\Models\OpenSource;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function githubRepoPayload(array $overrides = []): array
{
    return [
        'fork' => false,
        'name' => 'laravel-docuware',
        'description' => 'DocuWare REST API package',
        'topics' => ['laravel', 'docuware'],
        'html_url' => 'https://github.com/codebar-ag/laravel-docuware',
        'full_name' => 'codebar-ag/laravel-docuware',
        'stargazers_count' => 12,
        'forks_count' => 3,
        'language' => 'PHP',
        ...$overrides,
    ];
}

it('syncs the fetched repositories and skips forks', function () {
    Http::preventStrayRequests();
    Http::fake([
        'api.github.com/orgs/codebar-ag/repos*' => Http::response([
            githubRepoPayload(),
            githubRepoPayload(['fork' => true, 'name' => 'forked-repo', 'full_name' => 'codebar-ag/forked-repo']),
        ]),
        'packagist.org/packages/*' => Http::response([
            'package' => ['downloads' => ['total' => 4321]],
        ]),
    ]);

    runArtisan('sync:repositories')->assertSuccessful();

    expect(OpenSource::count())->toBe(1);

    $entry = OpenSource::where('slug', 'laravel-docuware')->firstOrFail();

    expect($entry->getTranslation('title', 'de_CH'))->toBe('Laravel Docuware')
        ->and($entry->getTranslation('teaser', 'en_CH'))->toBe('DocuWare REST API package')
        ->and($entry->published)->toBeTrue()
        ->and($entry->tags)->toBe(['laravel', 'docuware'])
        ->and($entry->link)->toBe('https://github.com/codebar-ag/laravel-docuware')
        ->and($entry->github_name)->toBe('codebar-ag/laravel-docuware')
        ->and($entry->downloads)->toBe(4321)
        ->and($entry->stars)->toBe(12)
        ->and($entry->forks)->toBe(3)
        ->and($entry->primary_language)->toBe('PHP');
})->group('open-source', 'console');

it('follows pagination until a page comes back short', function () {
    $fullPage = array_map(
        fn (int $index): array => githubRepoPayload([
            'name' => sprintf('repo-%03d', $index),
            'full_name' => sprintf('codebar-ag/repo-%03d', $index),
        ]),
        range(1, 100),
    );

    Http::preventStrayRequests();
    Http::fake([
        'api.github.com/orgs/codebar-ag/repos*' => Http::sequence()
            ->push($fullPage)
            ->push([githubRepoPayload(['name' => 'repo-101', 'full_name' => 'codebar-ag/repo-101'])]),
        'packagist.org/packages/*' => Http::response([
            'package' => ['downloads' => ['total' => 0]],
        ]),
    ]);

    runArtisan('sync:repositories')->assertSuccessful();

    expect(OpenSource::count())->toBe(101);
})->group('open-source', 'console');

it('sends the configured token as a bearer header', function () {
    config()->set('services.github.token', 'secret-token');

    Http::preventStrayRequests();
    Http::fake([
        'api.github.com/orgs/codebar-ag/repos*' => Http::response([]),
    ]);

    runArtisan('sync:repositories')->assertSuccessful();

    Http::assertSent(fn (Request $request): bool => $request->hasHeader('Authorization', 'Bearer secret-token'));
})->group('open-source', 'console');

it('fails without writing anything when the GitHub API errors', function () {
    Http::preventStrayRequests();
    Http::fake([
        'api.github.com/orgs/codebar-ag/repos*' => Http::response('rate limited', 500),
    ]);

    runArtisan('sync:repositories')->assertFailed();

    expect(OpenSource::count())->toBe(0);
})->group('open-source', 'console');

it('records zero downloads when packagist does not know the package', function () {
    Http::preventStrayRequests();
    Http::fake([
        'api.github.com/orgs/codebar-ag/repos*' => Http::response([githubRepoPayload()]),
        'packagist.org/packages/*' => Http::response('not found', 404),
    ]);

    runArtisan('sync:repositories')->assertSuccessful();

    expect(OpenSource::where('slug', 'laravel-docuware')->firstOrFail()->downloads)->toBe(0);
})->group('open-source', 'console');
