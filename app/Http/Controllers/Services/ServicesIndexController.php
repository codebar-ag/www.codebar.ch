<?php

namespace App\Http\Controllers\Services;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Content\ContentItem;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ServicesIndexController extends Controller
{
    public function __invoke(ViewDataAction $data): View
    {
        $locale = app()->getLocale();

        return view('app.services.index')->with([
            'page' => PageAction::for('services.index', $locale),
            'services' => $data->services($locale)->groupBy(fn (ContentItem $item) => $item->frontmatter['group'] ?? 'Services'),
        ]);
    }
}
