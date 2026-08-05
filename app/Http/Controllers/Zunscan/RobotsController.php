<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(Request $request): Response
    {
        // Deliberately not config('app.url'): AppServiceProvider forces every
        // absolute URL Laravel generates to the main site's host, so this domain's
        // own sitemap has to be built from the request instead.
        $sitemapUrl = $request->getSchemeAndHttpHost().'/sitemap.xml';

        return response(content: $this->content($sitemapUrl), headers: [
            'Content-Type' => 'text/plain',
        ]);
    }

    private function content(string $sitemapUrl): string
    {
        if (! app()->isProduction()) {
            return implode("\n", [
                'User-agent: *',
                'Disallow: /',
            ]);
        }

        return implode("\n", [
            'User-agent: *',
            'Disallow:',
            '',
            'Sitemap: '.$sitemapUrl,
        ]);
    }
}
