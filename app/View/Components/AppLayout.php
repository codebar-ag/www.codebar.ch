<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\LocaleEnum;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * @param  array<int, array<string, mixed>>  $schema
     *                                                    Page-specific schema.org nodes appended to the global graph
     *                                                    (LocalBusiness, BlogPosting, Person, …). See App\Seo\SchemaGraph.
     */
    public function __construct(
        protected mixed $page,
        public bool $preconnectCloudinary = false,
        protected array $schema = [],
        /**
         * Editorial pages manage their own width so figures can break out of the
         * reading column. Everything else stays inside the shared max-w-4xl frame.
         */
        public bool $wide = false,
    ) {}

    public function render(): View
    {
        $locale = app()->getLocale();

        return view('layouts.app')->with([
            'locales' => LocaleEnum::cases(),
            'locale' => Str::slug($locale),
            'page' => $this->page,
            'preconnectCloudinary' => $this->preconnectCloudinary,
            'schema' => $this->schema,
            'wide' => $this->wide,
        ]);
    }
}
