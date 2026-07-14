<?php

namespace App\Http\Controllers\Technologies;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TechnologiesShowController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(string $locale, Technology $technology): View
    {
        return view('app.technologies.show')->with([
            'page' => (new PageAction(locale: $locale))->technology(technology: $technology),
            'name' => $technology->title,
            'teaser' => $technology->teaser,
            'content' => Str::of($technology->content ?? '')->markdown(),
            'tags' => $technology->tags,
        ]);
    }
}
