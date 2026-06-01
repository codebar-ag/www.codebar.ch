<?php

namespace App\Console\Commands;

use App\Content\MarkdownContentService;
use Illuminate\Console\Command;
use Spatie\ResponseCache\Facades\ResponseCache;

class ContentCacheClearCommand extends Command
{
    protected $signature = 'content:clear';

    protected $description = 'Flush the markdown content cache and the response cache.';

    public function handle(MarkdownContentService $content): int
    {
        $content->flush();
        ResponseCache::clear();
        $this->info('Markdown content cache and response cache cleared.');

        return self::SUCCESS;
    }
}
