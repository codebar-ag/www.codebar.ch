<?php

namespace App\View\Components;

use App\Actions\ViewDataAction;
use App\Enums\LocaleEnum;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function __construct(protected mixed $page) {}

    public function render(): View
    {
        $locale = app()->getLocale();
        $data = app(ViewDataAction::class);

        return view('layouts.app')->with([
            'locales' => LocaleEnum::cases(),
            'locale' => Str::slug($locale),
            'page' => $this->page,
            'configuration' => $data->configuration($locale),
            'services' => $data->services($locale),
            'products' => $data->products($locale),
        ]);
    }
}
