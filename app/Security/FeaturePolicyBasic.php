<?php

declare(strict_types=1);

namespace App\Security;

use Mazedlx\FeaturePolicy\Directive;
use Mazedlx\FeaturePolicy\Policies\Policy;
use Mazedlx\FeaturePolicy\Value;

class FeaturePolicyBasic extends Policy
{
    public function configure(): void
    {
        $this->addDirective(Directive::GEOLOCATION, Value::SELF)
            ->addDirective(Directive::FULLSCREEN, Value::SELF);
    }
}
