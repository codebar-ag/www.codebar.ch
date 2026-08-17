<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use App\Http\Controllers\AboutUs\AboutUsIndexController;
use App\Http\Controllers\Ai\AiIndexController;
use App\Http\Controllers\Ai\AiLlmAnalyticsIndexController;
use App\Http\Controllers\Ai\AiLlmIndexController;
use App\Http\Controllers\Contact\ContactIndexController;
use App\Http\Controllers\CoWorking\CoWorkingIndexController;
use App\Http\Controllers\Entry\EntryIndexController;
use App\Http\Controllers\Jobs\JobsIndexController;
use App\Http\Controllers\Legal\ImprintIndexController;
use App\Http\Controllers\Legal\PrivacyIndexController;
use App\Http\Controllers\Legal\TermsIndexController;
use App\Http\Controllers\Media\MediaIndexController;
use App\Http\Controllers\Network\NetworkIndexController;
use App\Http\Controllers\Network\NetworkManageShowController;
use App\Http\Controllers\Network\NetworkManageUpdateController;
use App\Http\Controllers\Network\NetworkRequestIndexController;
use App\Http\Controllers\Network\NetworkRequestStoreController;
use App\Http\Controllers\Network\NetworkShowController;
use App\Http\Controllers\News\NewsIndexController;
use App\Http\Controllers\News\NewsShowController;
use App\Http\Controllers\OpenSource\OpenSourceIndexController;
use App\Http\Controllers\OpenSource\OpenSourceShowController;
use App\Http\Controllers\Products\ProductsIndexController;
use App\Http\Controllers\Products\ProductsShowController;
use App\Http\Controllers\Robots\RobotsController;
use App\Http\Controllers\Services\ServicesDmsEcmIndexController;
use App\Http\Controllers\Services\ServicesDocuwareExportIndexController;
use App\Http\Controllers\Services\ServicesIndexController;
use App\Http\Controllers\Services\ServicesShowController;
use App\Http\Controllers\Sitemap\SitemapController;
use App\Http\Controllers\Start\StartIndexController;
use App\Http\Controllers\Technologies\TechnologiesIndexController;
use App\Http\Controllers\Technologies\TechnologiesShowController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;
use Spatie\Honeypot\ProtectAgainstSpam;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;

$locales = array_column(LocaleEnum::cases(), 'value');

// Registered first: its own robots.txt/sitemap.xml routes (in routes/zunscan.php)
// must win over the unscoped ones below, which have no domain constraint.
Route::domain(config()->string('zunscan.domain'))->group(base_path('routes/zunscan.php'));

Route::get('/', EntryIndexController::class)->name('entry.index');

Route::group(['as' => Str::slug(LocaleEnum::EN->value).'.'], function () use ($locales) {
    Route::get('en-ch', StartIndexController::class)->name('start.index');

    Route::get('news', NewsIndexController::class)->name('news.index');
    Route::get('news/{locale}/{news}', NewsShowController::class)->whereIn('locale', $locales)->name('news.show');

    Route::get('about-us', AboutUsIndexController::class)->name('about-us.index');

    Route::get('services', ServicesIndexController::class)->name('services.index');
    Route::get('services/dms-ecm', ServicesDmsEcmIndexController::class)->name('services.dms-ecm.index');
    Route::get('services/dms-ecm/docuware-export', ServicesDocuwareExportIndexController::class)->name('services.dms-ecm.docuware-export.index');
    Route::get('services/{locale}/{service}', ServicesShowController::class)->whereIn('locale', $locales)->name('services.show');

    Route::get('products', ProductsIndexController::class)->name('products.index');
    Route::get('products/{locale}/{product}', ProductsShowController::class)->whereIn('locale', $locales)->name('products.show');

    Route::get('technologies', TechnologiesIndexController::class)->name('technologies.index');
    Route::get('technologies/{locale}/{technology}', TechnologiesShowController::class)->whereIn('locale', $locales)->name('technologies.show');

    Route::get('co-working', CoWorkingIndexController::class)->name('co-working.index');

    Route::get('open-source-contributions', OpenSourceIndexController::class)->name('open-source.index');
    Route::get('open-source-contributions/{locale}/{openSource}', OpenSourceShowController::class)->whereIn('locale', $locales)->name('open-source.show');

    Route::get('ai', AiIndexController::class)->name('ai.index');
    Route::get('ai/llm', AiLlmIndexController::class)->name('ai.llm.index');
    Route::get('ai/llm-analytics', AiLlmAnalyticsIndexController::class)->name('ai.llm.analytics.index');

    Route::get('network', NetworkIndexController::class)->name('network.index');
    Route::get('network/request', NetworkRequestIndexController::class)->middleware(DoNotCacheResponse::class)->name('network.request.index');
    Route::post('network/request', NetworkRequestStoreController::class)->middleware(['throttle:5,1', ProtectAgainstSpam::class])->name('network.request.store');
    Route::get('network/manage/{networkUser}', NetworkManageShowController::class)->middleware(['signed', DoNotCacheResponse::class])->name('network.manage.show');
    Route::put('network/manage/{networkUser}', NetworkManageUpdateController::class)->middleware('signed')->name('network.manage.update');
    Route::get('network/{slug}', NetworkShowController::class)->name('network.show');

    Route::get('legal/privacy', PrivacyIndexController::class)->name('legal.privacy.index');
    Route::get('legal/imprint', ImprintIndexController::class)->name('legal.imprint.index');
    Route::get('legal/terms', TermsIndexController::class)->name('legal.terms.index');

    Route::get('jobs', JobsIndexController::class)->name('jobs.index');
    Route::get('media', MediaIndexController::class)->name('media.index');
    // Not cached: the opening hours box reflects the current day and open/closed state.
    Route::get('contact', ContactIndexController::class)->middleware(DoNotCacheResponse::class)->name('contact.index');
});

Route::group(['as' => Str::slug(LocaleEnum::DE->value).'.'], function () use ($locales) {
    Route::get('de-ch', StartIndexController::class)->name('start.index');

    Route::get('aktuelles', NewsIndexController::class)->name('news.index');
    Route::get('aktuelles/{locale}/{news}', NewsShowController::class)->whereIn('locale', $locales)->name('news.show');
    Route::get('ueber-uns', AboutUsIndexController::class)->name('about-us.index');

    Route::get('dienstleistungen', ServicesIndexController::class)->name('services.index');
    Route::get('dienstleistungen/dms-ecm', ServicesDmsEcmIndexController::class)->name('services.dms-ecm.index');
    Route::get('dienstleistungen/dms-ecm/docuware-export', ServicesDocuwareExportIndexController::class)->name('services.dms-ecm.docuware-export.index');
    Route::get('dienstleistungen/{locale}/{service}', ServicesShowController::class)->whereIn('locale', $locales)->name('services.show');

    Route::get('produkte', ProductsIndexController::class)->name('products.index');
    Route::get('produkte/{locale}/{product}', ProductsShowController::class)->whereIn('locale', $locales)->name('products.show');

    Route::get('technologien', TechnologiesIndexController::class)->name('technologies.index');
    Route::get('technologien/{locale}/{technology}', TechnologiesShowController::class)->whereIn('locale', $locales)->name('technologies.show');

    Route::get('arbeitsplaetze', CoWorkingIndexController::class)->name('co-working.index');

    Route::get('open-source-beitraege', OpenSourceIndexController::class)->name('open-source.index');
    Route::get('open-source-beitraege/{locale}/{openSource}', OpenSourceShowController::class)->whereIn('locale', $locales)->name('open-source.show');

    Route::get('ki', AiIndexController::class)->name('ai.index');
    Route::get('ki/llm', AiLlmIndexController::class)->name('ai.llm.index');
    Route::get('ki/llm-analytics', AiLlmAnalyticsIndexController::class)->name('ai.llm.analytics.index');

    Route::get('netzwerk', NetworkIndexController::class)->name('network.index');
    Route::get('netzwerk/request', NetworkRequestIndexController::class)->middleware(DoNotCacheResponse::class)->name('network.request.index');
    Route::post('netzwerk/request', NetworkRequestStoreController::class)->middleware(['throttle:5,1', ProtectAgainstSpam::class])->name('network.request.store');
    Route::get('netzwerk/verwalten/{networkUser}', NetworkManageShowController::class)->middleware(['signed', DoNotCacheResponse::class])->name('network.manage.show');
    Route::put('netzwerk/verwalten/{networkUser}', NetworkManageUpdateController::class)->middleware('signed')->name('network.manage.update');
    Route::get('netzwerk/{slug}', NetworkShowController::class)->name('network.show');

    Route::get('rechtliches/datenschutz', PrivacyIndexController::class)->name('legal.privacy.index');
    Route::get('rechtliches/impressum', ImprintIndexController::class)->name('legal.imprint.index');
    Route::get('rechtliches/geschaeftsbedingungen', TermsIndexController::class)->name('legal.terms.index');

    Route::get('stellen', JobsIndexController::class)->name('jobs.index');
    Route::get('medien', MediaIndexController::class)->name('media.index');
    // Not cached: the opening hours box reflects the current day and open/closed state.
    Route::get('kontakt', ContactIndexController::class)->middleware(DoNotCacheResponse::class)->name('contact.index');
});

Route::get('robots.txt', RobotsController::class);
Route::get('sitemap.xml', SitemapController::class);

Route::get('health', HealthCheckJsonResultsController::class)
    ->middleware(['throttle:12,1', DoNotCacheResponse::class])
    ->name('health');
