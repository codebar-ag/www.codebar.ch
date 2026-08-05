<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    private const array PAGES = [
        'start.index',
        'about.index',
        'services.scanning.show',
        'contact.index',
        'media.index',
        'terms.index',
        'privacy.index',
    ];

    private const array LOCALES = ['de-ch', 'en-ch'];

    public function __invoke(Request $request): Response
    {
        $host = $request->getSchemeAndHttpHost();

        $urls = collect(self::LOCALES)
            ->crossJoin(self::PAGES)
            ->map(fn (array $pair): string => sprintf(
                '<url><loc>%s</loc></url>',
                e($host.route('zunscan.'.$pair[0].'.'.$pair[1], absolute: false))
            ))
            ->implode('');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            .$urls
            .'</urlset>';

        return response(content: $xml)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
