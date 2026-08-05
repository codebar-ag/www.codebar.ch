<?php

declare(strict_types=1);

namespace App\Http\Controllers\Zunscan;

use App\Enums\LocaleEnum;
use App\Http\Controllers\Controller;

abstract class ZunscanController extends Controller
{
    /**
     * The share card, one per language.
     *
     * Self-hosted rather than the old Cloudinary page photo: that image was the
     * bare logo on a blue field, which preview inspectors flag for carrying no
     * headline — a share card has to say what the page is about on its own.
     * These are 1200×630, matching the dimensions the layout declares.
     *
     * asset() resolves against the root URL that the Zunscan middleware pins to
     * the current host, so this stays absolute and on the right domain even
     * though AppServiceProvider forces the main site's root app-wide.
     */
    protected function ogImage(): string
    {
        $suffix = app()->getLocale() === LocaleEnum::EN->value ? 'en' : 'de';

        return asset("images/seo/og-zunscan-{$suffix}.jpg");
    }
}
