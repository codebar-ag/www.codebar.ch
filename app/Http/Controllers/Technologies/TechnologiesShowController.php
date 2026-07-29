<?php

declare(strict_types=1);

namespace App\Http\Controllers\Technologies;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TechnologiesShowController extends Controller
{
    public function __invoke(string $locale, Technology $technology): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        return view('app.technologies.show')->with([
                    'page' => (new PageAction(locale: $locale))->technology(technology: $technology),
                    'name' => $technology->title,
                    'teaser' => $technology->teaser,
                    'content' => Str::of($technology->content ?? '')->markdown(),
                    'tags' => $technology->tags,
                ]);*/
    }
}
