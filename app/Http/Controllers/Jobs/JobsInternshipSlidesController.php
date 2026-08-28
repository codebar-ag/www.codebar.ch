<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Actions\ViewDataAction;
use App\DTO\ContactDTO;
use App\Enums\ContactSectionEnum;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class JobsInternshipSlidesController extends Controller
{
    private const array MENTOR_KEYS = ['tobias-brogle', 'julian-leipert'];

    public function __invoke(ViewDataAction $viewData): View
    {
        $mentors = $viewData
            ->contactsInSection(app()->getLocale(), ContactSectionEnum::EMPLOYEES)
            ->filter(fn (ContactDTO $contact): bool => in_array($contact->key, self::MENTOR_KEYS, true))
            ->values();

        return view('app.jobs.slides')->with([
            'mentors' => $mentors,
        ]);
    }
}
