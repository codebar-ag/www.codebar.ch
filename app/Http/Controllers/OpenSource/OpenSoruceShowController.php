<?php

namespace App\Http\Controllers\OpenSource;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\OpenSource;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OpenSoruceShowController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(string $locale, OpenSource $openSource): View
    {
        return view('app.open-source.show')->with([
            'page' => (new PageAction(locale: $locale))->openSource(openSource: $openSource),
            'name' => $openSource->title,
            'teaser' => $openSource->teaser,
            'content' => Str::of($openSource->content ?? '')->markdown(),
            'tags' => $openSource->tags,
        ]);
    }
}
