<?php

use App\Http\Controllers\AboutUs\AboutUsIndexController;
use App\Http\Controllers\Contact\ContactIndexController;
use App\Http\Controllers\Legal\ImprintIndexController;
use App\Http\Controllers\Locale\LocaleUpdateController;
use App\Http\Controllers\News\NewsIndexController;
use App\Http\Controllers\News\NewsShowController;
use App\Http\Controllers\Products\ProductsIndexController;
use App\Http\Controllers\Products\ProductsShowController;
use App\Http\Controllers\Services\ServicesIndexController;
use App\Http\Controllers\Services\ServicesShowController;
use App\Http\Controllers\Start\StartIndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', StartIndexController::class)->name('start.index');

Route::get('news', NewsIndexController::class)->name('news.index');
Route::get('news/{news}', NewsShowController::class)->name('news.show');

Route::get('about-us', AboutUsIndexController::class)->name('about-us.index');

Route::get('services', ServicesIndexController::class)->name('services.index');
Route::get('services/{service}', ServicesShowController::class)->name('services.show');

Route::get('products', ProductsIndexController::class)->name('products.index');
Route::get('products/{product}}', ProductsShowController::class)->name('products.show');

// Route::get('legal/terms', StartIndexController::class)->name('terms.index');
// Route::get('legal/privacy', StartIndexController::class)->name('privacy.index');

Route::get('legal/imprint', ImprintIndexController::class)->name('imprint.index');

// Route::get('jobs', StartIndexController::class)->name('jobs.index');
// Route::get('media', StartIndexController::class)->name('media.index');

Route::get('contact', ContactIndexController::class)->name('contact.index');

Route::get('locale/update/{locale}', LocaleUpdateController::class)->name('locale.update');
