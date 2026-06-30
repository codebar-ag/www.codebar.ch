<?php

namespace App\Http\Controllers\Media;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MediaIndexController extends Controller
{
    /**
     * Display the media page with downloadable logos.
     */
    public function __invoke(): View
    {
        return view('app.media.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'media.index'))->default(),
            'logos' => [
                [
                    'slug' => 'codebar-logo-colored',
                    'label' => __('Logo colored'),
                ],
                [
                    'slug' => 'codebar-logo-colored-inverted',
                    'label' => __('Logo colored inverted'),
                ],
                [
                    'slug' => 'codebar-logo-black-white',
                    'label' => __('Logo black white'),
                ],
                [
                    'slug' => 'codebar-logo-white-black',
                    'label' => __('Logo white black'),
                ],
            ],
        ]);
    }
}
