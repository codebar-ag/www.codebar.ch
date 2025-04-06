<?php

use App\Console\Commands\GenerateSitemap;

Schedule::command(GenerateSitemap::class)->everyMinute();
