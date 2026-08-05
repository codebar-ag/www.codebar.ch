<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use Illuminate\View\View;

class ServicesScanningController extends ZunscanController
{
    public function __invoke(): View
    {
        return view('zunscan.services.show.scanning', [
            'title' => __('zunscan.seo.scanning.title'),
            'description' => __('zunscan.seo.scanning.description'),
            'image' => $this->ogImage(),
        ]);
    }
}
