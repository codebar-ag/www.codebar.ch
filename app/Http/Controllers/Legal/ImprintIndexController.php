<?php

namespace App\Http\Controllers\Legal;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ImprintIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.legal.imprint.index')->with([
            'page' => PageAction::for('legal.imprint.index'),
        ]);
    }
}
