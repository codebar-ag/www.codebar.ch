<?php

namespace App\Providers;

use App\Actions\ViewDataAction;
use App\Checks\FailedJobsCheck;
use App\Checks\FilesystemsDefaultCheck;
use App\Checks\JobsCheck;
use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
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
        //
    }

    public function boot(): void
    {
        $this->multilanguage();

        Model::unguard();
        Model::shouldBeStrict($this->app->isLocal());

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

        View::share('configuration', Schema::hasTable('configurations') ? (new ViewDataAction)->configuration(app()->getLocale()) : null);

    }

    private function multilanguage(): void
    {
        News::registerLocalizedBinding('news');
        Service::registerLocalizedBinding('service');
        Product::registerLocalizedBinding('product');
    }
}
