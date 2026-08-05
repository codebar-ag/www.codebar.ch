<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TermsController extends ZunscanController
{
    public function __invoke(): View
    {
        return view('zunscan.legal.terms', [
            'title' => __('zunscan.seo.imprint.title'),
            'description' => __('zunscan.seo.imprint.description'),
            'image' => self::OG_IMAGE,
            'body' => Str::markdown(File::get(resource_path('content/zunscan/'.app()->getLocale().'/impressum.md'))),
        ]);
    }
}
