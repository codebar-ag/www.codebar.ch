<?php

namespace App\Http\Controllers\Legal;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PrivacyIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        // @todo Notification
        /*        return view('app.legal.privacy.index')->with([
                    'page' => (new PageAction(locale: null, routeName: 'legal.privacy.index'))->default(),
                ]);*/
    }
}
