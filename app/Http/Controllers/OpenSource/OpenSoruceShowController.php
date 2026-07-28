<?php

namespace App\Http\Controllers\OpenSource;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\OpenSource;
use App\Seo\SchemaNodes;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OpenSoruceShowController extends Controller
{
    public function __invoke(string $locale, OpenSource $openSource): View
    {
        // `sync:repositories` creates an entry per GitHub repository but only
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
        ]);
    }
}
