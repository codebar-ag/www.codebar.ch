<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Actions\StoreApplicationDocumentsAction;
use App\Enums\ApplicationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Jobs\ApplicationUpdateRequest;
use App\Jobs\Applications\SendApplicationSubmittedJob;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ApplicationUpdateController extends Controller
{
    public function __invoke(
        ApplicationUpdateRequest $request,
        Application $application,
        StoreApplicationDocumentsAction $storeDocuments,
    ): RedirectResponse {
        abort_if($application->isSubmitted(), 403);

        $application->update($request->applicationAttributes());

        $storeDocuments($application, $request->file('documents', []));

        $url = $this->freshSignedUrl($application);

        if (! $request->isSubmitAction()) {
            return redirect()->to($url)->with('status', __('Your application has been saved.'));
        }

        $application->update([
            'status' => ApplicationStatusEnum::Submitted,
            'submitted_at' => now(),
        ]);

        SendApplicationSubmittedJob::dispatch($application->id, app()->getLocale());

        return redirect()->to($url)->with('status', __('Your application has been submitted. Thank you — we will get back to you personally.'));
    }

    private function freshSignedUrl(Application $application): string
    {
        return URL::temporarySignedRoute(
            Str::slug(app()->getLocale()).'.jobs.internship.application.show',
            now()->addDays(7),
            ['application' => $application],
        );
    }
}
