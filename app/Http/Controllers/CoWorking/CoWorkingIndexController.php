<?php

declare(strict_types=1);

namespace App\Http\Controllers\CoWorking;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CoWorkingIndexController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');
    }
}
