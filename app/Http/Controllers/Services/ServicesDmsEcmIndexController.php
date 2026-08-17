<?php

declare(strict_types=1);

namespace App\Http\Controllers\Services;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Markdown\NewsMarkdown;
use App\Models\Service;
use App\Seo\SchemaNodes;
use Illuminate\View\View;

class ServicesDmsEcmIndexController extends Controller
{
    /**
     * The service whose body this page renders. The markdown under
     * database/files/services stays the single source for what we offer, so the
     * page and the teaser on the expertise index can never say different things.
     */
    private const string SERVICE_SLUG = 'dms-ecm-consulting';

    public function __invoke(NewsMarkdown $markdown): View
    {
        $locale = app()->getLocale();
        $service = Service::query()->where('slug', self::SERVICE_SLUG)->first();

        $body = $service instanceof Service && is_string($service->content) ? $service->content : '';

        return view('app.services.dms-ecm.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'services.dms-ecm.index'))->default(),
            'content' => $markdown->toHtml($body),
            'schema' => $service instanceof Service
                ? SchemaNodes::services(collect([$service]), $locale)
                : [],
        ]);
    }
}
