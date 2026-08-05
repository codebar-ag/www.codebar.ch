<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use Illuminate\View\View;

class MediaController extends ZunscanController
{
    public function __invoke(): View
    {
        return view('zunscan.media.index', [
            'title' => __('zunscan.seo.media.title'),
            'description' => __('zunscan.seo.media.description'),
            'image' => $this->ogImage(),
        ]);
    }
}
