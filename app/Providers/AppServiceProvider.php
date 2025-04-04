<?php

namespace App\Providers;

use App\Checks\FailedJobsCheck;
use App\Checks\FilesystemsDefaultCheck;
use App\Checks\JobsCheck;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Facades\Health;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Model::shouldBeStrict($this->app->isLocal());

        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Indigo,
            'success' => Color::Green,
            'warning' => Color::Amber,
        ]);

        Health::checks([
            DebugModeCheck::new(),
            CacheCheck::new(),
            OptimizedAppCheck::new(),
            EnvironmentCheck::new()->if(app()->isProduction()),
            FilesystemsDefaultCheck::new()->everyFiveMinutes(),
            JobsCheck::new()->everyFiveMinutes(),
            FailedJobsCheck::new(),
            SecurityAdvisoriesCheck::new()->lastDayOfMonth(),
        ]);
    }
}
