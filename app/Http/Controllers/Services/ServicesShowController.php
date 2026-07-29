<?php

declare(strict_types=1);

namespace App\Http\Controllers\Services;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServicesShowController extends Controller
{
    public function __invoke(string $locale, Service $service): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        return view('app.services.show')->with([
                    'page' => (new PageAction(locale: $locale, routeName: null))->service(service: $service, withReferences: true),
                    'name' => $service->name,
                    'teaser' => $service->teaser,
                    'content' => Str::of($service->content ?? '')->markdown(),
                    'tags' => $service->tags,
                ]);*/
    }
}
