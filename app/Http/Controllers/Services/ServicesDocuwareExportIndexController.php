<?php

declare(strict_types=1);

namespace App\Http\Controllers\Services;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Seo\SchemaNodes;
use Illuminate\View\View;

class ServicesDocuwareExportIndexController extends Controller
{
    public function __invoke(): View
    {
        $page = (new PageAction(locale: null, routeName: 'services.dms-ecm.docuware-export.index'))->default();

        return view('app.services.dms-ecm.docuware-export')->with([
            'page' => $page,
            'schema' => $page === null ? [] : SchemaNodes::docuwareExport($page, app()->getLocale()),
        ]);
    }
}
