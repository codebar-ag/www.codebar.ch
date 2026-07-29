<?php

declare(strict_types=1);

namespace App\Http\Controllers\Technologies;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TechnologiesIndexController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        $locale = app()->getLocale();

                return view('app.technologies.index')->with([
                    'page' => (new PageAction(locale: null, routeName: 'technologies.index'))->default(),
                    'technologies' => (new ViewDataAction)->technologies($locale),
                ]);*/
    }
}
