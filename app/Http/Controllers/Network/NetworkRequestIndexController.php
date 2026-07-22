<?php

namespace App\Http\Controllers\Network;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class NetworkRequestIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.network.request')->with([
            'page' => (new PageAction(locale: null, routeName: 'network.request.index'))->default(),
        ]);
    }
}
