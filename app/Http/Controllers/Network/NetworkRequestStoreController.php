<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Http\Requests\Network\NetworkRequestStoreRequest;
use App\Jobs\Network\SendNetworkManageLinkJob;
use Illuminate\Http\RedirectResponse;

class NetworkRequestStoreController extends Controller
{
    public function __invoke(NetworkRequestStoreRequest $request): RedirectResponse
    {
        SendNetworkManageLinkJob::dispatch(
            $request->string('email')->value(),
            app()->getLocale(),
        );

        // Always the same response, so email addresses cannot be enumerated.
        return redirect()
            ->to(localized_route('network.request.index'))
            ->with('status', __('If the email address is registered, we have sent you a link.'));
    }
}
