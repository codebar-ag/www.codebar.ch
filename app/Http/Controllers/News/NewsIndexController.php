<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class NewsIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.start.index');
    }
}
