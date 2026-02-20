<?php

namespace App\Http\Controllers\Ai;

use App\Actions\PageAction;
use App\Data\GkiServiceData;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AiShowController extends Controller
{
    public function __invoke(string $slug): View
    {
        $service = GkiServiceData::findBySlug($slug);

        abort_unless((bool) $service, 404);

        return view('app.ai.show')->with([
            'page' => (new PageAction(locale: null, routeName: 'ai.index'))->default(),
            'service' => $service,
        ]);
    }
}
