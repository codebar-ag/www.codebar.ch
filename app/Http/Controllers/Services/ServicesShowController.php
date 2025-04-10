<?php

namespace App\Http\Controllers\Services;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServicesShowController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(string $locale, Service $service): View
    {
        return view('app.services.show')->with([
            'page' => (new PageAction(locale: $locale, routeName: null))->service(service: $service),
            'name' => $service->name,
            'teaser' => $service->teaser,
            'content' => Str::of($service->content)->markdown(),
            'tags' => $service->tags,
        ]);
    }
}
