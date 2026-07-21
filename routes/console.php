<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('llm:fetch-analytics')->twiceDaily(5, 17);
