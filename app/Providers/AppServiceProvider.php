<?php

namespace App\Providers;

use App\Checks\FailedJobsCheck;
use App\Checks\FilesystemsDefaultCheck;
use App\Checks\JobsCheck;
use App\Content\MarkdownContentService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Facades\Health;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MarkdownContentService::class, fn () => new MarkdownContentService(
            basePath: config('content.path'),
            cacheTtl: (int) config('content.cache_ttl'),
        ));
    }

    public function boot(): void
    {
        View::share('configuration', site_configuration());

        Health::checks([
            DebugModeCheck::new(),
            CacheCheck::new(),
            OptimizedAppCheck::new(),
            EnvironmentCheck::new()->if(app()->isProduction()),
            JobsCheck::new()->everyFiveMinutes(),
            FailedJobsCheck::new(),
            FilesystemsDefaultCheck::new(),
            SecurityAdvisoriesCheck::new()->lastDayOfMonth(),
        ]);
    }
}
