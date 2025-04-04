<?php

namespace App\Http\Controllers\Locale;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LocaleUpdateController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.start.index');
    }
}
