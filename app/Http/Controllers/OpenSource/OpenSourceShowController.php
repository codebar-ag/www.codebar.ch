<?php

declare(strict_types=1);

namespace App\Http\Controllers\OpenSource;

use App\Http\Controllers\Controller;
use App\Models\OpenSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class OpenSourceShowController extends Controller
{
    /**
     * Disabled alongside the index. With no entry point left, a detail page would only
     * be reachable from a stale external link, so send those to the start page instead
     * of serving an orphan.
     */
    public function __invoke(string $locale, OpenSource $openSource): RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');
    }
}
