<?php

declare(strict_types=1);

namespace App\Http\Controllers\Technologies;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class TechnologiesIndexController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');
    }
}
