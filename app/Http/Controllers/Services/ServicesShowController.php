<?php

declare(strict_types=1);

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ServicesShowController extends Controller
{
    public function __invoke(string $locale, Service $service): RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');
    }
}
