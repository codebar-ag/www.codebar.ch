<?php

declare(strict_types=1);

namespace App\Security;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Honeypot\SpamResponder\SpamResponder;

class RedirectBackSpamResponder implements SpamResponder
{
    public function respond(Request $request, Closure $next): RedirectResponse
    {
        return redirect()->back()->withInput($request->except('_token'));
    }
}
