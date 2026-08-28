<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Jobs\ApplicationRequestStoreRequest;
use App\Jobs\Applications\SendApplicationLinkJob;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ApplicationRequestStoreController extends Controller
{
    public function __invoke(ApplicationRequestStoreRequest $request): RedirectResponse
    {
        $email = Str::lower($request->string('email')->value());

        Application::query()->firstOrCreate([
            'job_key' => Application::JOB_KEY_INTERNSHIP,
            'email' => $email,
        ]);

        SendApplicationLinkJob::dispatch(
            Application::JOB_KEY_INTERNSHIP,
            $email,
            app()->getLocale(),
        );

        return redirect()
            ->to(localized_route('jobs.internship.show'))
            ->with('status', __('We sent a link to :email. Please also check your spam folder.', ['email' => '<strong>'.e($email).'</strong>']));
    }
}
