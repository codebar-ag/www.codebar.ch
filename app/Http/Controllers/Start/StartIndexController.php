<?php

namespace App\Http\Controllers\Start;

use App\Actions\LocaleAction;
use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StartIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        $locale = request()->routeIs(Str::slug(LocaleEnum::EN->value).'.start.index')
            ? LocaleEnum::EN->value
            : LocaleEnum::DE->value;

        (new LocaleAction($locale))->setLocale();

        return view('app.start.index')->with([
            'page' => (new PageAction('start.index'))->default(),
            'news' => (new ViewDataAction)->news($locale),
        ]);
    }
}
