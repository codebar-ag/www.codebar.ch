<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use Illuminate\View\View;

class AboutController extends ZunscanController
{
    public function __invoke(): View
    {
        return view('zunscan.about.index', [
            'title' => __('zunscan.seo.about.title'),
            'description' => __('zunscan.seo.about.description'),
            'image' => $this->ogImage(),
        ]);
    }
}
