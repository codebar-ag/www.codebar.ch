<?php

declare(strict_types=1);

use App\Http\Controllers\Zunscan\AboutController;
use App\Http\Controllers\Zunscan\ContactController;
use App\Http\Controllers\Zunscan\MediaController;
use App\Http\Controllers\Zunscan\PrivacyController;
use App\Http\Controllers\Zunscan\RobotsController;
use App\Http\Controllers\Zunscan\ServicesScanningController;
use App\Http\Controllers\Zunscan\SitemapController;
use App\Http\Controllers\Zunscan\StartController;
use App\Http\Controllers\Zunscan\TermsController;
use App\Http\Middleware\Zunscan\SetZunscanLanguage;
use Illuminate\Support\Facades\Route;

// Registered ahead of the main site's unscoped robots.txt/sitemap.xml routes
// (see routes/web.php) so they win the match for this domain.
Route::get('robots.txt', RobotsController::class);
Route::get('sitemap.xml', SitemapController::class);

Route::middleware(SetZunscanLanguage::class)->group(function () {
    // The domain root was never a language choice, same reasoning as the main
    // site's EntryIndexController — German is the default, always, not
    // whatever a session happens to remember. Registered inside this group,
    // not above it, so SetZunscanLanguage has already corrected the forced
    // root URL to this domain before the redirect target is built.
    Route::redirect('/', '/de-ch', 301);

    Route::group(['as' => 'zunscan.de-ch.'], function () {
        Route::get('de-ch', StartController::class)->name('start.index');
        Route::get('de-ch/about', AboutController::class)->name('about.index');
        Route::get('de-ch/services/scanning', ServicesScanningController::class)->name('services.scanning.show');
        Route::get('de-ch/kontakt', ContactController::class)->name('contact.index');
        Route::get('de-ch/medien', MediaController::class)->name('media.index');
        Route::get('de-ch/impressum', TermsController::class)->name('terms.index');
        Route::get('de-ch/datenschutz', PrivacyController::class)->name('privacy.index');
    });

    Route::group(['as' => 'zunscan.en-ch.'], function () {
        Route::get('en-ch', StartController::class)->name('start.index');
        Route::get('en-ch/about', AboutController::class)->name('about.index');
        Route::get('en-ch/services/scanning', ServicesScanningController::class)->name('services.scanning.show');
        Route::get('en-ch/contact', ContactController::class)->name('contact.index');
        Route::get('en-ch/media', MediaController::class)->name('media.index');
        Route::get('en-ch/imprint', TermsController::class)->name('terms.index');
        Route::get('en-ch/privacy', PrivacyController::class)->name('privacy.index');
    });
});
