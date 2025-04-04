<?php

use App\Http\Controllers\News\NewsIndexController;
use App\Http\Controllers\News\NewsShowController;
use App\Http\Controllers\Start\StartIndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', StartIndexController::class)->name('start.index');

Route::get('news', NewsIndexController::class)->name('news.index');
Route::get('news/{news}', NewsShowController::class)->name('news.show');

Route::get('about-us', StartIndexController::class)->name('about-us.index');

Route::get('services', StartIndexController::class)->name('services.index');
Route::get('services/dms-ecm', StartIndexController::class)->name('services.dms.index');

Route::get('products', StartIndexController::class)->name('products.index');
Route::get('products/docuhub', StartIndexController::class)->name('products.docuhub.index');
Route::get('products/clouddocs', StartIndexController::class)->name('products.clouddocs.index');

Route::get('products/clouddocs', StartIndexController::class)->name('products.clouddocs.index');

// Route::get('legal/terms', StartIndexController::class)->name('terms.index');
// Route::get('legal/privacy', StartIndexController::class)->name('privacy.index');

Route::get('legal/imprint', StartIndexController::class)->name('imprint.index');

// Route::get('jobs', StartIndexController::class)->name('jobs.index');
// Route::get('media', StartIndexController::class)->name('media.index');

Route::get('contact', StartIndexController::class)->name('contact.index');

Route::get('locale/{locale}', StartIndexController::class)->name('locale.update');
