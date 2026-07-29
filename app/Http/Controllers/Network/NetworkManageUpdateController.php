<?php

declare(strict_types=1);

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Http\Requests\Network\NetworkManageUpdateRequest;
use App\Models\NetworkUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class NetworkManageUpdateController extends Controller
{
    public function __invoke(NetworkManageUpdateRequest $request, NetworkUser $networkUser): RedirectResponse
    {
        // Hard whitelist: a signed link may only ever touch the person's own
        // name, contact channels, avatar, their own visibility and the
        // company website. Email is deliberately excluded — only codebar can
        // change it, so it's never read from the request even if submitted.
        $attributes = [
            'name' => $request->validated('name'),
            'public_email' => $request->validated('public_email'),
            'linkedin' => $request->validated('linkedin'),
            'phone' => $request->validated('phone'),
            'published' => $request->boolean('published'),
        ];

        if ($request->hasFile('avatar')) {
            // Raw upload goes to S3 as-is and is never displayed directly; display
            // always uses avatar_url (Cloudinary, set by codebar) with Gravatar as
            // fallback. The *_url columns are read-only on the signed link.
            $path = $request->file('avatar')->storePubliclyAs(
                'network/avatars',
                $networkUser->id.'-'.Str::random(8).'.'.$request->file('avatar')->extension(),
                's3',
            );

            abort_if($path === false, 500, 'Failed to store the uploaded avatar.');

            $attributes['avatar_disk'] = 's3';
            $attributes['avatar_path'] = $path;
        }

        $networkUser->update($attributes);

        $networkAttributes = [
            'website' => $request->validated('website'),
        ];

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->storePubliclyAs(
                'network/covers',
                Str::slug($networkUser->network_key).'-'.Str::random(8).'.'.$request->file('cover')->extension(),
                's3',
            );

            abort_if($path === false, 500, 'Failed to store the uploaded company image.');

            $networkAttributes['cover_disk'] = 's3';
            $networkAttributes['cover_path'] = $path;
        }

        $networkUser->network?->update($networkAttributes);

        $url = URL::temporarySignedRoute(
            Str::slug(app()->getLocale()).'.network.manage.show',
            now()->addHour(),
            ['networkUser' => $networkUser],
        );

        return redirect()
            ->to($url)
            ->with('status', __('Your profile has been updated.'));
    }
}
