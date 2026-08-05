<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PrivacyController extends ZunscanController
{
    public function __invoke(): View
    {
        return view('zunscan.legal.privacy', [
            'title' => __('zunscan.seo.privacy.title'),
            'description' => __('zunscan.seo.privacy.description'),
            'image' => $this->ogImage(),
            'body' => Str::markdown(File::get(resource_path('content/zunscan/'.app()->getLocale().'/datenschutz.md'))),
        ]);
    }
}
