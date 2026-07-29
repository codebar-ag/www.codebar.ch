<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('llm:fetch-analytics')->hourly();
Schedule::command('optimize:clear')->hourly();
Schedule::command('responsecache:clear')->hourly();
