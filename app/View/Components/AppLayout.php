<?php

namespace App\View\Components;

use App\Enums\LocaleEnum;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function __construct(protected mixed $page) {}

    public function render(): View
    {
        return view('layouts.app')->with([
            'locales' => LocaleEnum::cases(),
            'locale' => Str::slug(app()->getLocale()),
            'page' => $this->page,
        ]);
    }
}
