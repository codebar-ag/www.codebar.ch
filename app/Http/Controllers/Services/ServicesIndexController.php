<?php

namespace App\Http\Controllers\Services;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use App\Seo\SchemaNodes;
use Illuminate\View\View;

class ServicesIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        $locale = app()->getLocale();
        $services = (new ViewDataAction)->services($locale);

        return view('app.services.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'services.index'))->default(),
            'services' => $services,
            'schema' => SchemaNodes::services($services, $locale),
        ]);
    }
}
