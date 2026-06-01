<?php

namespace App\Http\Controllers\Technologies;

use App\Actions\PageAction;
use App\Content\MarkdownContentService;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TechnologiesShowController extends Controller
{
    public function __invoke(MarkdownContentService $content, string $locale, string $technology): View
    {
        $localeEnum = LocaleEnum::from($locale);
        $item = $content->find('technologies', $localeEnum, $technology) ?? abort(404);

        return view('app.technologies.show')->with([
            'page' => PageAction::fromContent($item),
            'name' => $item->title,
            'teaser' => $item->teaser,
            'content' => $item->body,
            'tags' => $item->tags(),
        ]);
    }
}
