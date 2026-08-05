<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use Illuminate\View\View;

class ContactController extends ZunscanController
{
    public function __invoke(): View
    {
        return view('zunscan.contact.index', [
            'title' => __('zunscan.seo.contact.title'),
            'description' => __('zunscan.seo.contact.description'),
            'image' => $this->ogImage(),
        ]);
    }
}
