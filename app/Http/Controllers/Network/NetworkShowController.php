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
            ->published()
            ->active()
            ->where('page_slug', $slug)
            ->first();

        abort_unless((bool) $network, 404);
        abort_unless(view()->exists('app.network.pages.'.$slug), 404);

        return view('app.network.pages.'.$slug)->with([
            // Built from the Network itself, not from the index page: otherwise
            // every partner page inherits the index's title and description and
            // its hreflang alternates point back at /netzwerk instead of at the
            // partner page's own translation.
            'page' => (new PageAction)->network(network: $network),
            'network' => $network,
            'users' => $network->publishedUsers()->get(),
        ]);
    }
}
