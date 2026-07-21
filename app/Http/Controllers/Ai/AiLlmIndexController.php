<?php

namespace App\Http\Controllers\Ai;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AiLlmIndexController extends Controller
{
    public function __invoke(): View
    {
        $viewData = new ViewDataAction;

        return view('app.ai.llm.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'ai.llm.index'))->default(),
            'groups' => $viewData->aiModelGroups(),
            'archive' => $viewData->aiModelArchive(),
        ]);
    }
}
