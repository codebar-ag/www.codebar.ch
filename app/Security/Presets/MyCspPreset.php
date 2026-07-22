<?php

namespace App\Security\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Value;

class MyCspPreset implements Preset
{
    public function configure(Policy $policy): void
    {
        $cdnHost = parse_url(config()->string('filesystems.disks.s3.cdn_endpoint', ''), PHP_URL_HOST);

        $scriptSources = array_filter([
            Keyword::SELF,
            'cdn.usefathom.com',
            'cdn-eu.usefathom.com',
            $cdnHost ?: null,
        ]);

        $policy->add(Directive::BASE, Keyword::SELF);

        $policy->add(Directive::DEFAULT, Keyword::SELF);

        $policy->add(Directive::FRAME_ANCESTORS, Keyword::SELF);

        $policy->add(Directive::UPGRADE_INSECURE_REQUESTS, Value::NO_VALUE);

        $policy->add(Directive::SCRIPT, $scriptSources);

        $policy->add(Directive::SCRIPT_ELEM, $scriptSources);

        $styleSources = array_filter([
            Keyword::SELF,
            Keyword::UNSAFE_INLINE,
            $cdnHost ?: null,
        ]);

        $policy->add(Directive::STYLE, $styleSources);

        $policy->add(Directive::STYLE_ELEM, $styleSources);

        $policy->add(Directive::IMG, [
            Keyword::SELF,
            'data:',
            'res.cloudinary.com',
            'www.gravatar.com',
        ]);

        $fontSources = array_filter([
            Keyword::SELF,
            $cdnHost ?: null,
        ]);

        $policy->add(Directive::FONT, $fontSources);
        $policy->add(Directive::FORM_ACTION, Keyword::SELF);
        $policy->add(Directive::MEDIA, Keyword::SELF);
        $policy->add(Directive::OBJECT, Keyword::NONE);
        $policy->add(Directive::CONNECT, [
            Keyword::SELF,
            'cdn.usefathom.com',
            'cdn-eu.usefathom.com',
        ]);
    }
}
