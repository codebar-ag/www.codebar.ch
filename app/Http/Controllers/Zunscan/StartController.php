<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use Illuminate\View\View;

class StartController extends ZunscanController
{
    public function __invoke(): View
    {
        return view('zunscan.start.index', [
            'title' => __('zunscan.seo.start.title'),
            'description' => __('zunscan.seo.start.description'),
            'image' => $this->ogImage(),
        ]);
    }
}
