<?php

namespace App\Actions;

use App\Enums\LocaleEnum;
use App\Enums\SessionKeyEnum;

class LocaleAction
{
    public function __construct(
        private string $locale,
    ) {}

    public function setLocale(): string
    {
        $locale = $this->validate() ? $this->locale : LocaleEnum::DE->value;

        session()->put(SessionKeyEnum::LANGUAGE->value, $locale);
        app()->setLocale($locale);

        return $locale;
    }

    private function validate(): bool
    {
        return in_array($this->locale, [
            LocaleEnum::DE->value,
            LocaleEnum::EN->value,
        ]);
    }
}
