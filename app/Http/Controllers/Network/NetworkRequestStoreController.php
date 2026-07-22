<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Http\Requests\Network\NetworkRequestStoreRequest;
use App\Jobs\Mail\NetworkManageLinkMail;
use App\Models\NetworkUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class NetworkRequestStoreController extends Controller
{
    public function __invoke(NetworkRequestStoreRequest $request): RedirectResponse
    {
        $networkUser = NetworkUser::query()
            ->where('email', $request->validated('email'))
            ->first();

        if ($networkUser) {
            $url = URL::temporarySignedRoute(
                Str::slug(app()->getLocale()).'.network.manage.show',
                now()->addHours(48),
                ['networkUser' => $networkUser],
            );

            Mail::to($networkUser->email)->send(new NetworkManageLinkMail($networkUser, $url));
        }

        // Always the same response, so email addresses cannot be enumerated.
        return redirect()
            ->to(localized_route('network.request.index'))
            ->with('status', __('If the email address is registered, we have sent you a link.'));
    }
}
