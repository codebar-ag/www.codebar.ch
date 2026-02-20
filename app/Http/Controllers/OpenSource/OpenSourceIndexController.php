<?php

namespace App\Http\Controllers\OpenSource;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\GithubRepository;
use Illuminate\View\View;

class OpenSourceIndexController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        $openSourceJson = GithubRepository::where('locale', $locale)
            ->where('published', true)
            ->orderByDesc('downloads')
            ->get()
            ->map(fn (GithubRepository $entry) => [
                'title' => $entry->title,
                'teaser' => $entry->teaser ?? '',
                'tags' => $entry->tags ?? [],
                'language' => $entry->primary_language,
                'url' => localized_route('open-source.show', [$locale, $entry]),
            ])
            ->values();

        return view('app.open-source.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'open-source.index'))->default(),
            'openSourceJson' => $openSourceJson->toJson(),
        ]);
    }
}
