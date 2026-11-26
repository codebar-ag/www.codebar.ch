<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\InfrastructureStack;
use Illuminate\Console\Command;

class ImportInfrastructureStacksCommand extends Command
{
    protected $signature = 'infrastructure:import-stacks {path? : Folder with one <slug>.json and an optional <slug>.webp screenshot or <slug>.svg sketch per customer}';

    protected $description = 'Import customer stacks for the infrastructure page from a private folder';

    public function handle(): int
    {
        $argument = $this->argument('path');
        $path = rtrim(is_string($argument) && $argument !== '' ? $argument : config()->string('infrastructure.customers_path'), '/');

        if (! is_dir($path)) {
            $this->error('Folder not found: '.$path);

            return self::FAILURE;
        }

        $imported = 0;

        foreach (glob($path.'/*.json') ?: [] as $file) {
            $slug = basename($file, '.json');
            $data = json_decode((string) file_get_contents($file), true);

            if (preg_match(InfrastructureStack::SLUG, $slug) !== 1 || ! is_array($data) || ! is_array($data['stations'] ?? null)) {
                $this->warn('Skipped '.basename($file));

                continue;
            }

            $overrides = [];

            foreach (is_array($data['overrides'] ?? null) ? $data['overrides'] : [] as $key => $override) {
                if (is_string($key) && is_array($override)) {
                    $overrides[$key] = $override;
                }
            }

            $stack = InfrastructureStack::query()->firstOrNew(['slug' => $slug]);

            if ($stack->is_default) {
                $this->warn('Skipped '.basename($file).' (default stack comes from the seeder)');

                continue;
            }

            $stack->fill([
                'name' => is_string($data['name'] ?? null) ? $data['name'] : $slug,
                'domain' => is_string($data['domain'] ?? null) ? $data['domain'] : $slug,
                'sketch' => $this->cover($path.'/'.$slug),
                'services' => array_values(array_filter($data['stations'], 'is_string')),
                'overrides' => $overrides,
            ])->save();

            $imported++;
        }

        $this->info($imported.' stacks imported.');

        return self::SUCCESS;
    }

    private function cover(string $base): ?string
    {
        if (is_file($base.'.webp')) {
            return 'data:image/webp;base64,'.base64_encode((string) file_get_contents($base.'.webp'));
        }

        return is_file($base.'.svg') ? (string) file_get_contents($base.'.svg') : null;
    }
}
