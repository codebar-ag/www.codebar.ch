<?php

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Models\GithubRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncRepositoriesCommand extends Command
{
    protected $signature = 'sync:repositories';

    protected $description = 'Sync public GitHub repositories from the codebar-ag organization';

    private const ORG = 'codebar-ag';

    private const DEFAULT_IMAGE = 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp';

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
            if ($repo['fork'] ?? false) {
                continue;
            }

            $this->syncRepository($repo);
            $synced++;
        }

        $this->info(sprintf('Synced %d repositories.', $synced));

        return self::SUCCESS;
    }

    private function fetchAllRepositories(): ?array
    {
        $repos = [];
        $page = 1;

        $headers = [];
        $token = config('services.github.token');
        if ($token) {
            $headers['Authorization'] = "Bearer {$token}";
        }

        do {
            $response = Http::withHeaders($headers)
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

            $batch = $response->json();

            if (empty($batch)) {
                break;
            }

            $repos = array_merge($repos, $batch);
            $page++;
        } while (count($batch) === 100);

        return $repos;
    }

    private function syncRepository(array $repo): void
    {
        $slug = Str::slug($repo['name']);
        $title = Str::of($repo['name'])->replace('-', ' ')->title()->toString();
        $teaser = $repo['description'] ?? '';
        $topics = $repo['topics'] ?? [];
        $downloads = $this->fetchPackagistDownloads($repo['full_name']);

        foreach (LocaleEnum::cases() as $locale) {
            $entry = GithubRepository::updateOrCreate(
                [
                    'locale' => $locale->value,
                    'slug' => $slug,
                ],
                [
                    'published' => true,
                    'title' => $title,
                    'teaser' => $teaser,
                    'image' => self::DEFAULT_IMAGE,
                    'tags' => $topics,
                    'link' => $repo['html_url'],
                    'downloads' => $downloads,
                    'stars' => $repo['stargazers_count'] ?? 0,
                    'forks' => $repo['forks_count'] ?? 0,
                    'primary_language' => $repo['language'],
                    'github_name' => $repo['full_name'],
                ]
            );

            $this->line(sprintf('  %s [%s] %s downloads', $entry->title, $locale->value, number_format($downloads)));
        }
    }

    private function fetchPackagistDownloads(string $fullName): int
    {
        $response = Http::accept('application/json')
            ->get(sprintf('https://packagist.org/packages/%s.json', $fullName));

        if ($response->failed()) {
            return 0;
        }

        return (int) data_get($response->json(), 'package.downloads.total', 0);
    }
}
