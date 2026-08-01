<?php

declare(strict_types=1);

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class NetworkShowController extends Controller
{
    public function __invoke(string $slug): RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');
    }
}
