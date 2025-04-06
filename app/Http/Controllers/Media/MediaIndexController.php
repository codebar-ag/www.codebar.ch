<?php

namespace App\Http\Controllers\Media;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MediaIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        return view('app.media.index')->with([
            'page' => (new PageAction('media.index'))->default(),
        ]);
    }
}
