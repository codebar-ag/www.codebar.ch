<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ApplicationFileDestroyController extends Controller
{
    public function __invoke(Application $application, ApplicationFile $applicationFile): RedirectResponse
    {
        abort_unless($applicationFile->application_id === $application->id, 404);
        abort_if($application->isSubmitted(), 403);

        $applicationFile->deleteFromDisk();
        $applicationFile->delete();

        $url = URL::temporarySignedRoute(
            Str::slug(app()->getLocale()).'.jobs.internship.application.show',
            now()->addDays(7),
            ['application' => $application],
        );

        return redirect()->to($url)->with('status', __('The document has been removed.'));
    }
}
