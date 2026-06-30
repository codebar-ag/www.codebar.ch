<?php

namespace App\Http\Controllers\Robots;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $sitemapUrl = rtrim(config('app.url'), '/').'/sitemap.xml';

        $content = implode("\n", [
            'User-agent: *',
            'Disallow:',
            '',
            'Sitemap: '.$sitemapUrl,
        ]);

        return response(content: $content, headers: [
            'Content-Type' => 'text/plain',
        ]);
    }
}
