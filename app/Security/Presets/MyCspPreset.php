<?php

namespace App\Security\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class MyCspPreset implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy->add(Directive::BASE, Keyword::SELF);

        $policy->add(Directive::DEFAULT, Keyword::SELF);

        $policy->add(Directive::SCRIPT, [
            Keyword::SELF,
            'cdn.usefathom.com',
            'cdn-eu.usefathom.com',
        ]);

        $policy->add(Directive::SCRIPT_ELEM, [
            Keyword::SELF,
            'cdn.usefathom.com',
            'cdn-eu.usefathom.com',
        ]);

        $policy->add(Directive::STYLE, [
            Keyword::SELF,
            Keyword::UNSAFE_INLINE,
        ]);

        $policy->add(Directive::STYLE_ELEM, [
            Keyword::SELF,
            Keyword::UNSAFE_INLINE,
        ]);

        $policy->add(Directive::IMG, [
            Keyword::SELF,
            'data:',
            'res.cloudinary.com',
        ]);

        $policy->add(Directive::FONT, Keyword::SELF);
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
