<?php

namespace App\Http\Controllers\Entry;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class EntryIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): RedirectResponse
    {
        $locale = Str::slug(app()->getLocale());

        return redirect()->route("{$locale}.start.index");
    }
}
