<?php

namespace App\Http\Controllers\News;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\DTO\PageDTO;
use App\Http\Controllers\Controller;
use App\Seo\SchemaNodes;
use Illuminate\View\View;

class NewsIndexController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        $page = (new PageAction(locale: null, routeName: 'news.index'))->default();
        $news = (new ViewDataAction)->news($locale);

        return view('app.news.index')->with([
            'page' => $page,
            'news' => $news,
            'schema' => $page instanceof PageDTO
                ? SchemaNodes::blog($news, $page, $locale)
                : [],
        ]);
    }
}
