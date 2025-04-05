<?php

namespace App\Http\Controllers\Services;

use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ServicesIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.services.index')->with([
            'services' => (new ViewDataAction)->services(),
        ]);
    }
}
