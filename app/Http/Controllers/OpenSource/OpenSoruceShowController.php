<?php

namespace App\Http\Controllers\OpenSource;

use App\Actions\PageAction;
use App\Content\MarkdownContentService;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class OpenSoruceShowController extends Controller
{
    public function __invoke(MarkdownContentService $content, string $locale, string $openSource): View
    {
        $localeEnum = LocaleEnum::from($locale);
        $item = $content->find('open-source', $localeEnum, $openSource) ?? abort(404);

        return view('app.open-source.show')->with([
            'page' => PageAction::fromContent($item),
            'name' => $item->title,
            'teaser' => $item->teaser,
            'content' => $item->body,
            'tags' => $item->tags(),
        ]);
    }
}
