<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

// A full sync window is dispatched per run; without the guard a slow LiteLLM
// response lets the next hour's run pile a second set of jobs on top.
Schedule::command('llm:fetch-analytics')->hourly()->withoutOverlapping();

// The opening-hours box renders "open now / closed" from the current time, so the
// rendered HTML goes stale on its own — no model change fires an observer for it.
Schedule::command('responsecache:clear')->hourly();
