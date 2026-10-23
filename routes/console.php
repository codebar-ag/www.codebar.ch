<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('llm:fetch-analytics')->hourly()->withoutOverlapping();

Schedule::command('applications:prune')->daily();

// Hourly, to match Laravel Cloud's 60-minute wake-up: Cloud wakes the app for every due task, so anything more frequent keeps it from hibernating.
Schedule::command('health:check')->hourly();
Schedule::command('health:schedule-check-heartbeat')->hourly();
