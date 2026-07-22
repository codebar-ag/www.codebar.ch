<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Http\Requests\Network\NetworkManageUpdateRequest;
use App\Jobs\Mail\NetworkProfileUpdatedMail;
use App\Models\Network;
use App\Models\NetworkUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Spatie\ResponseCache\Facades\ResponseCache;

class NetworkManageUpdateController extends Controller
{
    public function __invoke(NetworkManageUpdateRequest $request, NetworkUser $networkUser): RedirectResponse
    {
        // Hard whitelist: a signed link may only ever touch the person's own
        // contact channels, avatar, their own visibility and the company website.
        $attributes = [
            'email' => $request->validated('email'),
            'linkedin' => $request->validated('linkedin'),
            'phone' => $request->validated('phone'),
            'published' => $request->boolean('published'),
        ];

        if ($request->hasFile('avatar')) {
            // Raw upload goes to S3 as-is; codebar converts it to Cloudinary
            // later by replacing the avatar URL (the original stays in the bucket).
            $path = $request->file('avatar')->storePubliclyAs(
                'network/avatars',
                $networkUser->id.'-'.Str::random(8).'.'.$request->file('avatar')->extension(),
                's3',
            );

            $attributes['avatar'] = Storage::disk('s3')->url($path);
        }

        $networkUser->update($attributes);

        Network::query()
            ->where('key', $networkUser->network_key)
            ->update(['website' => $request->validated('website')]);

        ResponseCache::clear();

        Mail::to(config('mail.from.address'))->send(new NetworkProfileUpdatedMail($networkUser->refresh()));

        $url = URL::temporarySignedRoute(
            Str::slug(app()->getLocale()).'.network.manage.show',
            now()->addHours(48),
            ['networkUser' => $networkUser],
        );

        return redirect()
            ->to($url)
            ->with('status', __('Your profile has been updated.'));
    }
}
