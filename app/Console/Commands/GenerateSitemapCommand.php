<?php

namespace App\Console\Commands;

use App\Content\MarkdownContentService;
use App\Http\Controllers\Sitemap\SitemapController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Pre-warm the cached sitemap XML.';

    public function handle(MarkdownContentService $content): int
    {
        Cache::forget('sitemap_xml');
        app(SitemapController::class)($content);
        $this->info('Sitemap regenerated.');

        return self::SUCCESS;
    }
}
