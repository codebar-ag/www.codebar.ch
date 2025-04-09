<?php

use App\Enums\LocaleEnum;
use App\Http\Controllers\AboutUs\AboutUsIndexController;
use App\Http\Controllers\Contact\ContactIndexController;
use App\Http\Controllers\Entry\EntryIndexController;
use App\Http\Controllers\Jobs\JobsIndexController;
use App\Http\Controllers\Legal\ImprintIndexController;
use App\Http\Controllers\Legal\PrivacyIndexController;
use App\Http\Controllers\Legal\TermsIndexController;
use App\Http\Controllers\Locale\LocaleUpdateController;
use App\Http\Controllers\Media\MediaIndexController;
use App\Http\Controllers\News\NewsShowController;
use App\Http\Controllers\Products\ProductsIndexController;
use App\Http\Controllers\Products\ProductsShowController;
use App\Http\Controllers\Services\ServicesIndexController;
use App\Http\Controllers\Services\ServicesShowController;
use App\Http\Controllers\Sitemap\SitemapController;
use App\Http\Controllers\Start\StartIndexController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', EntryIndexController::class)->name('entry.index');

Route::group(['as' => Str::slug(LocaleEnum::EN->value).'.'], function () {
    Route::get('en-ch', StartIndexController::class)->name('start.index');
    Route::get('news/{news}', NewsShowController::class)->name('news.show');
    Route::get('about-us', AboutUsIndexController::class)->name('about-us.index');
    Route::get('services', ServicesIndexController::class)->name('services.index');
    Route::get('services/{locale}/{service}', ServicesShowController::class)->name('services.show');
    Route::get('products', ProductsIndexController::class)->name('products.index');
    Route::get('products/{product}', ProductsShowController::class)->name('products.show');
    // Route::get('legal/privacy', PrivacyIndexController::class)->name('legal.privacy.index');
    // Route::get('legal/terms', TermsIndexController::class)->name('legal.terms.index');
    Route::get('legal/imprint', ImprintIndexController::class)->name('legal.imprint.index');
    // Route::get('jobs', JobsIndexController::class)->name('jobs.index');
    // Route::get('media', MediaIndexController::class)->name('media.index');
    Route::get('contact', ContactIndexController::class)->name('contact.index');
});

Route::group(['as' => Str::slug(LocaleEnum::DE->value).'.'], function () {
    Route::get('de-ch', StartIndexController::class)->name('start.index');
    Route::get('aktuelles/{news}', NewsShowController::class)->name('news.show');
    // Route::get('ueber-uns', AboutUsIndexController::class)->name('about-us.index');
    Route::get('dienstleistungen', ServicesIndexController::class)->name('services.index');
    Route::get('dienstleistungen/{locale}/{service}', ServicesShowController::class)->name('services.show');
    Route::get('produkte', ProductsIndexController::class)->name('products.index');
    Route::get('produkte/{product}', ProductsShowController::class)->name('products.show');
    // Route::get('rechtlichtes/datenschutz', PrivacyIndexController::class)->name('legal.privacy.index');
    // Route::get('rechtlichtes/geschaeftsbedingungen', TermsIndexController::class)->name('legal.terms.index');
    Route::get('rechtlichtes/impressum', ImprintIndexController::class)->name('legal.imprint.index');
    // Route::get('stellen', JobsIndexController::class)->name('jobs.index');
    // Route::get('medien', MediaIndexController::class)->name('media.index');
    Route::get('kontakt', ContactIndexController::class)->name('contact.index');
});

Route::post('language/update', LocaleUpdateController::class)->name('language.update');

Route::get('sitemap.xml', [SitemapController::class, 'index']);
Route::get('sitemap-de-ch.xml', [SitemapController::class, 'deCH'])->name('de-ch.sitemap');
Route::get('sitemap-en-ch.xml', [SitemapController::class, 'enCH'])->name('en-en.sitemap');
