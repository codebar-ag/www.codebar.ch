<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\DTO\ContactDTO;
use App\DTO\PageDTO;
use App\Enums\ContactSectionEnum;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosition;
use App\Seo\SchemaNodes;
use Illuminate\View\View;

class JobsInternshipShowController extends Controller
{
    private const array MENTOR_KEYS = ['tobias-brogle', 'julian-leipert'];

    public function __invoke(ViewDataAction $viewData): View
    {
        $position = JobPosition::query()->where('key', Application::JOB_KEY_INTERNSHIP)->first();

        $mentors = $viewData
            ->contactsInSection(app()->getLocale(), ContactSectionEnum::EMPLOYEES)
            ->filter(fn (ContactDTO $contact): bool => in_array($contact->key, self::MENTOR_KEYS, true))
            ->values();

        $page = (new PageAction(locale: null, routeName: 'jobs.internship.show'))->default();

        $title = $position?->getTranslation('title', 'de_CH');
        $withSchema = $page instanceof PageDTO && $position !== null && $position->isOpen() && is_string($title);

        return view('app.jobs.internship')->with([
            'page' => $page,
            'position' => $position,
            'mentors' => $mentors,
            'schema' => $withSchema ? SchemaNodes::internshipJobPosting($page, $title) : [],
        ]);
    }
}
