<?php

namespace App\Http\Controllers\CoWorking;

use App\Actions\PageAction;
use App\Content\ContentItem;
use App\Content\MarkdownContentService;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CoWorkingIndexController extends Controller
{
    public function __invoke(MarkdownContentService $content): View
    {
        $locale = LocaleEnum::from(app()->getLocale());
        /** @var ContentItem $item */
        $item = $content->find('co-working', $locale, 'page') ?? abort(404);

        return view('app.co-working.index')->with([
            'page' => PageAction::fromContent($item),
            'item' => $item,
        ]);
    }
}
