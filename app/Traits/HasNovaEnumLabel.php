<?php

namespace App\Traits;

trait HasNovaEnumLabel
{
    // Nova Integration
    public function label(): string
    {
        return $this->getLabel();
    }
}
