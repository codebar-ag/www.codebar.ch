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

        $policy->add(Directive::CONNECT, Keyword::SELF);
        $policy->add(Directive::DEFAULT, Keyword::SELF);
        $policy->add(Directive::FONT, Keyword::SELF);
        $policy->add(Directive::FORM_ACTION, Keyword::SELF);
        $policy->add(Directive::IMG, [
            Keyword::SELF,
            'data:',
        ]);
        $policy->add(Directive::MEDIA, Keyword::SELF);
        $policy->add(Directive::OBJECT, Keyword::NONE);

        $policy->add(Directive::SCRIPT, [
            Keyword::SELF,
            Keyword::UNSAFE_EVAL,
            // Keyword::UNSAFE_INLINE,
        ]);

        $policy->add(Directive::STYLE, [
            Keyword::SELF,
            // Keyword::UNSAFE_EVAL,
            Keyword::UNSAFE_INLINE,
        ]);

        // Fathom Analytics
        $policy->add(Directive::SCRIPT, 'cdn.usefathom.com');
        $policy->add(Directive::CONNECT, 'cdn.usefathom.com');
        $policy->add(Directive::SCRIPT, 'cdn-eu.usefathom.com');
        $policy->add(Directive::CONNECT, 'cdn-eu.usefathom.com');
    }
}
