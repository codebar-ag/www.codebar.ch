<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use App\Http\Controllers\Controller;

abstract class ZunscanController extends Controller
{
    /**
     * The share image, pinned to the 1200×630 the layout declares in
     * og:image:width/height — the previous c_scale,dpr_2.0 transform produced
     * whatever size the source happened to be, so the declared dimensions
     * would not have matched the file. f_jpg rather than f_auto: social
     * crawlers are not browsers and several still do not accept webp.
     */
    protected const string OG_IMAGE = 'https://res.cloudinary.com/codebar/image/upload/c_fill,g_auto,w_1200,h_630,f_jpg,q_auto/www-paperflakes-ch/pages/x7img596iuglmttin6ro.jpg';
}
