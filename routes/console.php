<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('llm:fetch-analytics')->hourly()->withoutOverlapping();
Schedule::command('responsecache:clear')->hourly();
