<?php

namespace App\Http\Controllers\OpenSource;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OpenSourceIndexController extends Controller
{
    /**
     * Disabled until the listing actually has entries. `sync:repositories` is
     * not scheduled, so this page rendered its intro and nothing else — an
     * empty URL that search engines read as thin content. Restore the body
     * below once repositories are synced and written up.
     */
    public function __invoke(): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        $locale = app()->getLocale();

                return view('app.open-source.index')->with([
                    'page' => (new PageAction(locale: null, routeName: 'open-source.index'))->default(),
                    'openSource' => (new ViewDataAction)->openSource($locale),
                ]);*/
    }
}
