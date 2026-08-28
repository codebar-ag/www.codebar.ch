<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class JobsInternshipQrController extends Controller
{
    public function __invoke(): View
    {
        return view('app.jobs.qr');
    }
}
