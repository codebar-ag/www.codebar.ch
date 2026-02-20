<?php

namespace App\Http\Controllers\OpenSource;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\GithubRepository;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OpenSourceShowController extends Controller
{
    public function __invoke(string $locale, GithubRepository $githubRepository): View
    {
        return view('app.open-source.show')->with([
            'page' => (new PageAction(locale: $locale))->githubRepository(githubRepository: $githubRepository),
            'name' => $githubRepository->title,
            'teaser' => $githubRepository->teaser,
            'content' => $githubRepository->content ? Str::of($githubRepository->content)->markdown() : null,
            'tags' => $githubRepository->tags,
            'stars' => $githubRepository->stars,
            'forks' => $githubRepository->forks,
            'primaryLanguage' => $githubRepository->primary_language,
            'githubUrl' => $githubRepository->link,
            'githubName' => $githubRepository->github_name,
        ]);
    }
}
