<?php

use App\Enums\LocaleEnum;
use App\Http\Controllers\AboutUs\AboutUsIndexController;
use App\Http\Controllers\Contact\ContactIndexController;
use App\Http\Controllers\CoWorking\CoWorkingIndexController;
use App\Http\Controllers\Entry\EntryIndexController;
use App\Http\Controllers\Jobs\JobsIndexController;
use App\Http\Controllers\Legal\ImprintIndexController;
use App\Http\Controllers\Legal\PrivacyIndexController;
use App\Http\Controllers\Legal\TermsIndexController;
use App\Http\Controllers\Locale\LocaleUpdateController;
use App\Http\Controllers\Media\MediaIndexController;
use App\Http\Controllers\News\NewsIndexController;
use App\Http\Controllers\News\NewsShowController;
use App\Http\Controllers\OpenSource\OpenSoruceShowController;
use App\Http\Controllers\OpenSource\OpenSourceIndexController;
use App\Http\Controllers\Products\ProductsIndexController;
use App\Http\Controllers\Products\ProductsShowController;
use App\Http\Controllers\Services\ServicesIndexController;
use App\Http\Controllers\Services\ServicesShowController;
use App\Http\Controllers\Sitemap\SitemapController;
use App\Http\Controllers\Start\StartIndexController;
use App\Http\Controllers\Styleguide\StyleguideIndexController;
use App\Http\Controllers\Technologies\TechnologiesIndexController;
use App\Http\Controllers\Technologies\TechnologiesShowController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

    Route::get('open-source-contributions', OpenSourceIndexController::class)->name('open-source.index');
    Route::get('open-source-contributions/{locale}/{openSource}', OpenSoruceShowController::class)->name('open-source.show');

    Route::get('legal/privacy', PrivacyIndexController::class)->name('legal.privacy.index');
    Route::get('legal/imprint', ImprintIndexController::class)->name('legal.imprint.index');
    Route::get('legal/terms', TermsIndexController::class)->name('legal.terms.index');

    Route::get('jobs', JobsIndexController::class)->name('jobs.index');
    Route::get('media', MediaIndexController::class)->name('media.index');
    Route::get('contact', ContactIndexController::class)->name('contact.index');

    Route::get('co-working-en', CoWorkingIndexController::class)->name('co-working.index');
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

    Route::get('open-source-beitraege', OpenSourceIndexController::class)->name('open-source.index');
    Route::get('open-source-beitraege/{locale}/{openSource}', OpenSoruceShowController::class)->name('open-source.show');

    Route::get('rechtlichtes/datenschutz', PrivacyIndexController::class)->name('legal.privacy.index');
    Route::get('rechtlichtes/impressum', ImprintIndexController::class)->name('legal.imprint.index');
    Route::get('rechtlichtes/geschaeftsbedingungen', TermsIndexController::class)->name('legal.terms.index');

    Route::get('stellen', JobsIndexController::class)->name('jobs.index');
    Route::get('medien', MediaIndexController::class)->name('media.index');
    Route::get('kontakt', ContactIndexController::class)->name('contact.index');

    Route::get('co-working-de', CoWorkingIndexController::class)->name('co-working.index');
});

Route::post('language/update', LocaleUpdateController::class)->name('language.update');

Route::get('styleguide', StyleguideIndexController::class)->name('styleguide.index');

Route::get('sitemap.xml', SitemapController::class);

require __DIR__.'/well-known.php';
