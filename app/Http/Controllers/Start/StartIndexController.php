<?php

namespace App\Http\Controllers\Start;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StartIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.start.index');
    }
}
