<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    public function handle(): void
    {
        $filePath = public_path('sitemap.xml');

        $sitemap = Sitemap::create();
        // $sitemap->add(Url::create(route('start.index')));
        $sitemap->writeToFile($filePath);
        $this->cleanFile($filePath);

    }

    protected function cleanFile(string $filePath): void
    {
        if (! file_exists($filePath)) {
            return;
        }

        $xmlContent = file_get_contents($filePath);

        $dom = new \DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlContent); // @phpstan-ignore-line

        $formattedXml = $dom->saveXML();

        $formattedXml = preg_replace('/\s+$/', '', $formattedXml); // @phpstan-ignore-line

        File::put($filePath, $formattedXml); // @phpstan-ignore-line
    }
}
