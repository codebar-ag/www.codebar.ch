<?php

declare(strict_types=1);

namespace App\Http\Controllers\OpenSource;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class OpenSourceIndexController extends Controller
{
    /**
     * Disabled until the listing actually has entries. `sync:repositories` is not
     * scheduled, so this page rendered its intro and nothing else — an empty URL that
     * search engines read as thin content.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');
    }
}
