<?php

namespace App\Http\Controllers\Network;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\NetworkUser;
use Illuminate\View\View;

class NetworkManageShowController extends Controller
{
    public function __invoke(NetworkUser $networkUser): View
    {
        return view('app.network.manage')->with([
            'page' => (new PageAction(locale: null, routeName: 'network.request.index'))->default(),
            'networkUser' => $networkUser,
            'network' => $networkUser->network(),
        ]);
    }
}
