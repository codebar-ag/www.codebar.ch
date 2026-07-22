<?php

use App\Enums\LocaleEnum;
use App\Http\Controllers\AboutUs\AboutUsIndexController;
use App\Http\Controllers\Ai\AiIndexController;
use App\Http\Controllers\Ai\AiLlmAnalyticsIndexController;
use App\Http\Controllers\Ai\AiLlmIndexController;
use App\Http\Controllers\Contact\ContactIndexController;
use App\Http\Controllers\CoWorking\CoWorkingIndexController;
use App\Http\Controllers\Demo\FlowsLayoutDemoController;
use App\Http\Controllers\Entry\EntryIndexController;
use App\Http\Controllers\Jobs\JobsIndexController;
use App\Http\Controllers\Legal\ImprintIndexController;
use App\Http\Controllers\Legal\PrivacyIndexController;
use App\Http\Controllers\Legal\TermsIndexController;
use App\Http\Controllers\Locale\LocaleUpdateController;
use App\Http\Controllers\Media\MediaIndexController;
use App\Http\Controllers\Network\NetworkIndexController;
use App\Http\Controllers\Network\NetworkManageShowController;
use App\Http\Controllers\Network\NetworkManageUpdateController;
use App\Http\Controllers\Network\NetworkRequestIndexController;
use App\Http\Controllers\Network\NetworkRequestStoreController;
use App\Http\Controllers\Network\NetworkShowController;
use App\Http\Controllers\News\NewsIndexController;
use App\Http\Controllers\News\NewsShowController;
use App\Http\Controllers\OpenSource\OpenSoruceShowController;
use App\Http\Controllers\OpenSource\OpenSourceIndexController;
use App\Http\Controllers\Products\ProductsIndexController;
use App\Http\Controllers\Products\ProductsShowController;
use App\Http\Controllers\Robots\RobotsController;
use App\Http\Controllers\Services\ServicesIndexController;
use App\Http\Controllers\Services\ServicesShowController;
use App\Http\Controllers\Sitemap\SitemapController;
use App\Http\Controllers\Start\StartIndexController;
use App\Http\Controllers\Technologies\TechnologiesIndexController;
use App\Http\Controllers\Technologies\TechnologiesShowController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;

Route::get('/', EntryIndexController::class)->name('entry.index');

Route::group(['as' => Str::slug(LocaleEnum::EN->value).'.'], function () {
    Route::get('en-ch', StartIndexController::class)->name('start.index');

    Route::get('news', NewsIndexController::class)->name('news.index');
    Route::get('news/{locale}/{news}', NewsShowController::class)->name('news.show');

    Route::get('about-us', AboutUsIndexController::class)->name('about-us.index');

    Route::get('services', ServicesIndexController::class)->name('services.index');
    Route::get('services/{locale}/{service}', ServicesShowController::class)->name('services.show');

    Route::get('products', ProductsIndexController::class)->name('products.index');
    Route::get('products/{locale}/{product}', ProductsShowController::class)->name('products.show');

    Route::get('technologies', TechnologiesIndexController::class)->name('technologies.index');
    Route::get('technologies/{locale}/{technology}', TechnologiesShowController::class)->name('technologies.show');

    // Not yet linked in navigation — built but inactive, same as Technologies above.
    Route::get('co-working', CoWorkingIndexController::class)->name('co-working.index');

    Route::get('open-source-contributions', OpenSourceIndexController::class)->name('open-source.index');
    Route::get('open-source-contributions/{locale}/{openSource}', OpenSoruceShowController::class)->name('open-source.show');

    Route::get('ai', AiIndexController::class)->name('ai.index');
    Route::get('ai/llm', AiLlmIndexController::class)->name('ai.llm.index');
    Route::get('ai/llm-analytics', AiLlmAnalyticsIndexController::class)->name('ai.llm.analytics.index');

    Route::get('network', NetworkIndexController::class)->name('network.index');
    Route::get('network/request', NetworkRequestIndexController::class)->middleware(DoNotCacheResponse::class)->name('network.request.index');
    Route::post('network/request', NetworkRequestStoreController::class)->middleware('throttle:5,1')->name('network.request.store');
    Route::get('network/manage/{networkUser}', NetworkManageShowController::class)->middleware(['signed', DoNotCacheResponse::class])->name('network.manage.show');
    Route::put('network/manage/{networkUser}', NetworkManageUpdateController::class)->middleware('signed')->name('network.manage.update');
    Route::get('network/{slug}', NetworkShowController::class)->name('network.show');

    Route::get('legal/privacy', PrivacyIndexController::class)->name('legal.privacy.index');
    Route::get('legal/imprint', ImprintIndexController::class)->name('legal.imprint.index');
    Route::get('legal/terms', TermsIndexController::class)->name('legal.terms.index');

    Route::get('jobs', JobsIndexController::class)->name('jobs.index');
    Route::get('media', MediaIndexController::class)->name('media.index');
    Route::get('contact', ContactIndexController::class)->name('contact.index');
});

Route::group(['as' => Str::slug(LocaleEnum::DE->value).'.'], function () {
    Route::get('de-ch', StartIndexController::class)->name('start.index');

    Route::get('aktuelles', NewsIndexController::class)->name('news.index');
    Route::get('aktuelles/{locale}/{news}', NewsShowController::class)->name('news.show');
    Route::get('ueber-uns', AboutUsIndexController::class)->name('about-us.index');

    Route::get('dienstleistungen', ServicesIndexController::class)->name('services.index');
    Route::get('dienstleistungen/{locale}/{service}', ServicesShowController::class)->name('services.show');

    Route::get('produkte', ProductsIndexController::class)->name('products.index');
    Route::get('produkte/{locale}/{product}', ProductsShowController::class)->name('products.show');

    Route::get('technologien', TechnologiesIndexController::class)->name('technologies.index');
    Route::get('technologien/{locale}/{technology}', TechnologiesShowController::class)->name('technologies.show');

    // Not yet linked in navigation — built but inactive, same as Technologies above.
    Route::get('co-working', CoWorkingIndexController::class)->name('co-working.index');

    Route::get('open-source-beitraege', OpenSourceIndexController::class)->name('open-source.index');
    Route::get('open-source-beitraege/{locale}/{openSource}', OpenSoruceShowController::class)->name('open-source.show');

    Route::get('ki', AiIndexController::class)->name('ai.index');
    Route::get('ki/llm', AiLlmIndexController::class)->name('ai.llm.index');
    Route::get('ki/llm-analytics', AiLlmAnalyticsIndexController::class)->name('ai.llm.analytics.index');

    Route::get('netzwerk', NetworkIndexController::class)->name('network.index');
    Route::get('netzwerk/request', NetworkRequestIndexController::class)->middleware(DoNotCacheResponse::class)->name('network.request.index');
    Route::post('netzwerk/request', NetworkRequestStoreController::class)->middleware('throttle:5,1')->name('network.request.store');
    Route::get('netzwerk/verwalten/{networkUser}', NetworkManageShowController::class)->middleware(['signed', DoNotCacheResponse::class])->name('network.manage.show');
    Route::put('netzwerk/verwalten/{networkUser}', NetworkManageUpdateController::class)->middleware('signed')->name('network.manage.update');
    Route::get('netzwerk/{slug}', NetworkShowController::class)->name('network.show');

    Route::get('rechtliches/datenschutz', PrivacyIndexController::class)->name('legal.privacy.index');
    Route::get('rechtliches/impressum', ImprintIndexController::class)->name('legal.imprint.index');
    Route::get('rechtliches/geschaeftsbedingungen', TermsIndexController::class)->name('legal.terms.index');

    Route::get('stellen', JobsIndexController::class)->name('jobs.index');
    Route::get('medien', MediaIndexController::class)->name('media.index');
    Route::get('kontakt', ContactIndexController::class)->name('contact.index');
});

Route::post('language/update', LocaleUpdateController::class)->name('language.update');

if (! app()->isProduction()) {
    Route::get('demo/flows', [FlowsLayoutDemoController::class, 'index'])->name('demo.flows.index');
    Route::get('demo/flows/v2', [FlowsLayoutDemoController::class, 'indexV2'])->name('demo.flows.v2.index');
    Route::get('demo/flows/v2/{variant}', [FlowsLayoutDemoController::class, 'showV2'])->name('demo.flows.v2.show');
    Route::get('demo/flows/{variant}', [FlowsLayoutDemoController::class, 'show'])->name('demo.flows.show');
}

Route::get('robots.txt', RobotsController::class);
Route::get('sitemap.xml', SitemapController::class);
