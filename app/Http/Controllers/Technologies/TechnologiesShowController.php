<?php

declare(strict_types=1);

namespace App\Http\Controllers\Technologies;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class TechnologiesShowController extends Controller
{
    public function __invoke(string $locale, Technology $technology): RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');
    }
}
