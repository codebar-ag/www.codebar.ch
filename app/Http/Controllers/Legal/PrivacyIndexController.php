<?php

declare(strict_types=1);

namespace App\Http\Controllers\Legal;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PrivacyIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.legal.privacy.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'legal.privacy.index'))->default(),
        ]);
    }
}
