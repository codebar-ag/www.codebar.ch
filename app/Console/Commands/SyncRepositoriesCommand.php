<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\OpenSource;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncRepositoriesCommand extends Command
{
    protected $signature = 'sync:repositories';

    protected $description = 'Sync public GitHub repositories from the codebar-ag organization into the open-source listing';

    private const string ORG = 'codebar-ag';

    private const string DEFAULT_IMAGE = 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp';

    public function handle(): int
    {
        $this->info('Fetching public repositories from GitHub...');

        $repos = $this->fetchAllRepositories();

        if ($repos === null) {
            $this->error('Failed to fetch repositories from GitHub.');

            return self::FAILURE;
        }

        $this->info(sprintf('Found %d public repositories.', count($repos)));

        $synced = 0;

        foreach ($repos as $repo) {
            if ($repo['fork']) {
                continue;
            }

            $this->syncRepository($repo);
            $synced++;
        }

        $this->info(sprintf('Synced %d repositories.', $synced));

        return self::SUCCESS;
    }

    /**
     * @return array<int, array{fork: bool, name: string, description: ?string, topics: array<int, string>, html_url: string, full_name: string, stargazers_count: int, forks_count: int, language: ?string}>|null
     */
    private function fetchAllRepositories(): ?array
    {
        $repos = [];
        $page = 1;
        $batch = [];

        $headers = [];
        $token = config('services.github.token');
        if (is_string($token) && $token !== '') {
            $headers['Authorization'] = "Bearer {$token}";
        }

        do {
            $response = $this->client()
                ->withHeaders($headers)
                ->accept('application/vnd.github+json')
                ->get(sprintf('https://api.github.com/orgs/%s/repos', self::ORG), [
                    'type' => 'public',
                    'per_page' => 100,
                    'page' => $page,
                ]);

            if ($response->failed()) {
                $this->error(sprintf('GitHub API error: %s', $response->body()));

                return null;
            }

            $json = $response->json();

            if (! is_array($json) || $json === []) {
                break;
            }

            $batch = array_map(
                fn (mixed $item): array => $this->normalizeRepo(is_array($item) ? $item : []),
                array_values($json),
            );

            $repos = array_merge($repos, $batch);
            $page++;
        } while (count($batch) === 100);

        return $repos;
    }

    /**
     * @param  array<array-key, mixed>  $repo
     * @return array{fork: bool, name: string, description: ?string, topics: array<int, string>, html_url: string, full_name: string, stargazers_count: int, forks_count: int, language: ?string}
     */
    private function normalizeRepo(array $repo): array
    {
        return [
            'fork' => $this->boolValue($repo['fork'] ?? null),
            'name' => $this->stringValue($repo['name'] ?? null),
            'description' => $this->nullableStringValue($repo['description'] ?? null),
            'topics' => $this->stringListValue($repo['topics'] ?? null),
            'html_url' => $this->stringValue($repo['html_url'] ?? null),
            'full_name' => $this->stringValue($repo['full_name'] ?? null),
            'stargazers_count' => $this->intValue($repo['stargazers_count'] ?? null),
            'forks_count' => $this->intValue($repo['forks_count'] ?? null),
            'language' => $this->nullableStringValue($repo['language'] ?? null),
        ];
    }

    private function boolValue(mixed $value): bool
    {
        return is_scalar($value) && $value;
    }

    private function stringValue(mixed $value): string
    {
        return is_scalar($value) ? (string) $value : '';
    }

    private function nullableStringValue(mixed $value): ?string
    {
        return is_scalar($value) ? (string) $value : null;
    }

    private function intValue(mixed $value): int
    {
        return is_numeric($value) ? (int) $value : 0;
    }

    /**
     * @return array<int, string>
     */
    private function stringListValue(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return array_values(array_map(
            fn (mixed $item): string => $this->stringValue($item),
            $value,
        ));
    }

    /**
     * @param  array{fork: bool, name: string, description: ?string, topics: array<int, string>, html_url: string, full_name: string, stargazers_count: int, forks_count: int, language: ?string}  $repo
     */
    private function syncRepository(array $repo): void
    {
        $slug = Str::slug($repo['name']);
        $title = Str::of($repo['name'])->replace('-', ' ')->title()->toString();
        $teaser = $repo['description'] ?? '';
        $downloads = $this->fetchPackagistDownloads($repo['full_name']);

        $entry = OpenSource::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'published' => true,
                'title' => ['de_CH' => $title, 'en_CH' => $title],
                'teaser' => ['de_CH' => $teaser, 'en_CH' => $teaser],
                'image' => self::DEFAULT_IMAGE,
                'tags' => $repo['topics'],
                'link' => $repo['html_url'],
                'downloads' => $downloads,
                'stars' => $repo['stargazers_count'],
                'forks' => $repo['forks_count'],
                'primary_language' => $repo['language'],
                'github_name' => $repo['full_name'],
            ]
        );

        $this->line(sprintf('  %s %s downloads', $entry->title, number_format($downloads)));
    }

    /**
     * A sync that walks every repository makes one call per repository, so a slow
     * endpoint must fail fast rather than hold the command open on the default timeout.
     */
    private function client(): PendingRequest
    {
        return Http::connectTimeout(5)
            ->timeout(20)
            ->retry(times: 2, sleepMilliseconds: 500, throw: false);
    }

    private function fetchPackagistDownloads(string $fullName): int
    {
        $response = $this->client()
            ->accept('application/json')
            ->get(sprintf('https://packagist.org/packages/%s.json', $fullName));

        if ($response->failed()) {
            return 0;
        }

        $downloads = data_get($response->json(), 'package.downloads.total', 0);

        return is_numeric($downloads) ? (int) $downloads : 0;
    }
}
