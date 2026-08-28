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

    private const string CONTACT_KEY = 'sebastian-buergin-fix';

    public function __invoke(ViewDataAction $viewData, ?int $slide = null): View
    {
        $employees = $viewData->contactsInSection(app()->getLocale(), ContactSectionEnum::EMPLOYEES);

        $mentors = $employees
            ->filter(fn (ContactDTO $contact): bool => in_array($contact->key, self::MENTOR_KEYS, true))
            ->values();

        $contact = $employees->first(fn (ContactDTO $contact): bool => $contact->key === self::CONTACT_KEY);

        return view('app.jobs.slides')->with([
            'mentors' => $mentors,
            'contact' => $contact,
            'start' => max(0, ($slide ?? 1) - 1),
        ]);
    }
}
