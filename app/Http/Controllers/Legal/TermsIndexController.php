<?php

namespace App\Http\Controllers\Legal;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TermsIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.legal.terms.index')->with([
            'page' => PageAction::for('legal.terms.index'),
        ]);
    }
}
