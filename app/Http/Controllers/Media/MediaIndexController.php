<?php

namespace App\Http\Controllers\Media;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MediaIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.media.index')->with([
            'page' => PageAction::for('media.index'),
        ]);
    }
}
