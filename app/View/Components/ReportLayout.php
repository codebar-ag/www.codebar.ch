<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ReportLayout extends Component
{
    /**
     * @param  array<int, array<string, mixed>>  $schema
     */
    public function __construct(
        protected mixed $page,
        protected array $schema = [],
    ) {}

    public function render(): View
    {
        return view('layouts.report')->with([
            'page' => $this->page,
            'schema' => $this->schema,
        ]);
    }
}
