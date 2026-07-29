<?php

declare(strict_types=1);

namespace App\Providers;

use App\Checks\FailedJobsCheck;
use App\Checks\FilesystemsDefaultCheck;
use App\Checks\JobsCheck;
use App\Models\AiModel;
use App\Models\Network;
use App\Models\NetworkUser;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;
use App\Observers\ContentCacheObserver;
use App\Observers\NetworkObserver;
use App\Observers\NetworkUserObserver;
use App\Observers\NewsObserver;
use App\Observers\SitemapCacheObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Facades\Health;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;
use Spatie\Translatable\Translatable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->multilanguage();

        app(Translatable::class)->allowNullForTranslation();

        Network::observe(NetworkObserver::class);
        NetworkUser::observe(NetworkUserObserver::class);
        News::observe(NewsObserver::class);

        // Everything the sitemap draws its URLs from.
        foreach ([Page::class, Network::class, News::class, OpenSource::class] as $model) {
            $model::observe(SitemapCacheObserver::class);
        }

        // Everything ViewDataAction caches forever. Without this the listings never
        // pick up an import, because there is no TTL to fall back on.
        foreach ([Service::class, Product::class, Technology::class, OpenSource::class, AiModel::class] as $model) {
            $model::observe(ContentCacheObserver::class);
        }

        // Staging, not just local: production-shaped data is where a lazy load first
        // shows up, and the cached listings in ViewDataAction rely on this to catch one.
        Model::shouldBeStrict(! $this->app->isProduction());

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
