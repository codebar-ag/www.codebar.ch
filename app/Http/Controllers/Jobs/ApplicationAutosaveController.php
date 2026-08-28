<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Actions\StoreApplicationDocumentsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Jobs\ApplicationSaveRequest;
use App\Models\Application;
use Illuminate\Http\JsonResponse;

class ApplicationAutosaveController extends Controller
{
    public function __invoke(
        ApplicationSaveRequest $request,
        Application $application,
        StoreApplicationDocumentsAction $storeDocuments,
    ): JsonResponse {
        abort_if($application->isSubmitted(), 403);

        $application->update($request->applicationAttributes());

        $documents = $request->file('documents', []);

        $storeDocuments($application, $documents);

        return response()->json([
            'saved_at' => now()->format('H:i:s'),
            'uploaded' => count($documents),
        ]);
    }
}
