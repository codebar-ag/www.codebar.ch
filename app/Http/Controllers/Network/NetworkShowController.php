<?php

namespace App\Http\Controllers\Network;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\View\View;

class NetworkShowController extends Controller
{
    public function __invoke(string $slug): View
    {
        $network = Network::query()
            ->where('locale', app()->getLocale())
            ->published()
            ->active()
            ->where('page_slug', $slug)
            ->first();

        abort_unless((bool) $network, 404);
        abort_unless(view()->exists('app.network.pages.'.$slug), 404);

        return view('app.network.pages.'.$slug)->with([
            'page' => (new PageAction(locale: null, routeName: 'network.index'))->default(),
            'network' => $network,
            'users' => $network->publishedUsers()->get(),
        ]);
    }
}
