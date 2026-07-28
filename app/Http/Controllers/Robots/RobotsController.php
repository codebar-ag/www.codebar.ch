<?php

namespace App\Http\Controllers\Robots;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        return response(content: $this->content(), headers: [
            'Content-Type' => 'text/plain',
        ]);
    }

    private function content(): string
    {
        // Staging and local copies must stay out of the index — an indexed
        // duplicate of the whole site competes with production for the same
        // queries. Only production invites crawlers in.
        if (! app()->isProduction()) {
            return implode("\n", [
                'User-agent: *',
                'Disallow: /',
            ]);
        }

        $sitemapUrl = rtrim(config()->string('app.url'), '/').'/sitemap.xml';

        return implode("\n", [
            'User-agent: *',
            'Disallow:',
            '',
            // Signed, personal and form-only URLs. Nothing here belongs in a
            // search result, and crawling them just burns crawl budget.
            'Disallow: /network/request',
            'Disallow: /netzwerk/request',
            'Disallow: /network/manage/',
            'Disallow: /netzwerk/verwalten/',
            '',
            // AI crawlers are deliberately allowed: being cited in generated
            // answers requires being crawlable in the first place.
            '',
            'Sitemap: '.$sitemapUrl,
        ]);
    }
}
