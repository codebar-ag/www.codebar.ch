<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('llm:fetch-analytics')->twiceDaily(5, 17);

Schedule::command('optimize:clear')->twiceDaily(6, 18);
Schedule::command('responsecache:clear')->twiceDaily(6, 18);
