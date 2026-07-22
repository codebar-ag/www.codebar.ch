<?php

namespace App\Providers;

use App\Checks\FailedJobsCheck;
use App\Checks\FilesystemsDefaultCheck;
use App\Checks\JobsCheck;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Check;
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

        $environmentCheck = EnvironmentCheck::new();
        $environmentCheck->if(app()->isProduction());

        Health::checks([
            DebugModeCheck::new(),
            CacheCheck::new(),
            OptimizedAppCheck::new(),
            $environmentCheck,
            self::asCheck(FilesystemsDefaultCheck::new()->everyFiveMinutes()),
            self::asCheck(JobsCheck::new()->everyFiveMinutes()),
            FailedJobsCheck::new(),
            self::asCheck(SecurityAdvisoriesCheck::new()->lastDayOfMonth()),
        ]);
    }

    private function multilanguage(): void
    {
        News::registerLocalizedBinding('news');
        Service::registerLocalizedBinding('service');
        Product::registerLocalizedBinding('product');
        Technology::registerLocalizedBinding('technology');
        OpenSource::registerLocalizedBinding('openSource');
    }

    private static function asCheck(Check $check): Check
    {
        return $check;
    }
}
