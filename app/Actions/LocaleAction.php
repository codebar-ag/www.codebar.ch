<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\SessionKeyEnum;

class LocaleAction
{
    public function __construct(
        private string $locale,
    ) {}

    public function setLocale(): string
    {
        session()->put(SessionKeyEnum::LANGUAGE->value, $this->locale);
        app()->setLocale($this->locale);

        return $this->locale;
    }
}
