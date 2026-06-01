<?php

namespace App\Http\Controllers\Styleguide;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StyleguideIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.styleguide.index')->with([
            'page' => PageAction::for('styleguide.index'),
        ]);
    }
}
