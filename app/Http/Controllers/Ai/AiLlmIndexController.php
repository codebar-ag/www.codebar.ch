<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ai;

use App\Actions\LlmUsageStatsAction;
use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AiLlmIndexController extends Controller
{
    public function __invoke(LlmUsageStatsAction $stats, ViewDataAction $viewData): View
    {
        return view('app.ai.llm.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'ai.llm.index'))->default(),
            'groups' => $viewData->aiModelGroups(),
            'archive' => $viewData->aiModelArchive(),
            'llmSummary' => $stats->currentMonthSummary(),
            'hasUsage' => $stats->totalSummary()['requests'] > 0,
        ]);
    }
}
