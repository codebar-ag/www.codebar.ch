<?php

namespace App\Http\Controllers\Legal;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PrivacyIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.legal.privacy.index')->with([
            'page' => (new PageAction('legal.privacy.index'))->default(),
        ]);
    }
}
