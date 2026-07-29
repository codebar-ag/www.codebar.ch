<?php

declare(strict_types=1);

namespace App\Http\Controllers\OpenSource;

use App\Http\Controllers\Controller;
use App\Models\OpenSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OpenSoruceShowController extends Controller
{
    /**
     * Disabled alongside the index. With no entry point left, a detail page
     * would only be reachable from a stale external link, so send those to the
     * start page instead of serving an orphan.
     */
    public function __invoke(string $locale, OpenSource $openSource): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        // `sync:repositories` creates an entry per GitHub repository but only
                // fills title and teaser — content is written by hand. Without it there
                // is no page here worth having, let alone indexing, so serve a 404
                // rather than a near-empty URL.
                if (! $openSource->hasWrittenContent()) {
                    throw new NotFoundHttpException;
                }

                $page = (new PageAction(locale: $locale))->openSource(openSource: $openSource, withReferences: true);

                return view('app.open-source.show')->with([
                    'page' => $page,
                    'name' => $openSource->title,
                    'teaser' => $openSource->teaser,
                    'content' => Str::of($openSource->content ?? '')->markdown(),
                    'tags' => $openSource->tags,
                    'link' => $openSource->link,
                    'schema' => SchemaNodes::softwareSourceCode($openSource, $page, $locale),
                ]);*/
    }
}
