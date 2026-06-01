<?php

namespace App\Http\Controllers\Services;

use App\Actions\PageAction;
use App\Content\MarkdownContentService;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ServicesShowController extends Controller
{
    public function __invoke(MarkdownContentService $content, string $locale, string $service): View
    {
        $localeEnum = LocaleEnum::from($locale);
        $item = $content->find('services', $localeEnum, $service) ?? abort(404);

        return view('app.services.show')->with([
            'page' => PageAction::fromContent($item),
            'name' => $item->title,
            'teaser' => $item->teaser,
            'content' => $item->body,
            'tags' => $item->tags(),
        ]);
    }
}
