<?php

namespace App\Http\Controllers\Ai;

use App\Actions\PageAction;
use App\Data\GkiServiceData;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AiIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.ai.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'ai.index'))->default(),
            'services' => GkiServiceData::all(),
        ]);
    }
}
