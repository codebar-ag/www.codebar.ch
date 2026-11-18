<?php

declare(strict_types=1);

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ErpRedirectController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->to(localized_route('erp.systems.pricing.show'));
    }
}
