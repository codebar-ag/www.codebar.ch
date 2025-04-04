<?php

namespace App\Security;

use Illuminate\Support\Facades\Config;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;

class SecurityPolicyBasic extends Policy
{
    public function configure(): void
    {
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Config::array('default.security_headers.connect'))
            ->addDirective(Directive::DEFAULT, Config::array('default.security_headers.default'))
            ->addDirective(Directive::FORM_ACTION, Config::array('default.security_headers.form_action'))
            ->addDirective(Directive::IMG, Config::array('default.security_headers.img'))
            ->addDirective(Directive::MEDIA, Config::array('default.security_headers.media'))
            ->addDirective(Directive::OBJECT, Config::array('default.security_headers.object'))
            ->addDirective(Directive::FONT, Config::array('default.security_headers.font'))
            ->addDirective(Directive::SCRIPT, Config::array('default.security_headers.script'))
            ->addDirective(Directive::STYLE_ELEM, Config::array('default.security_headers.style_elem'))
            ->addDirective(Directive::STYLE, Config::array('default.security_headers.style'));
    }
}
