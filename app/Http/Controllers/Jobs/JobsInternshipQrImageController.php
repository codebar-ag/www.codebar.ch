<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Support\QrCodeSvg;
use Illuminate\Http\Response;

class JobsInternshipQrImageController extends Controller
{
    public function __invoke(): Response
    {
        $svg = QrCodeSvg::render(localized_route('jobs.internship.show'));

        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
