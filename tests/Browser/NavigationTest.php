<?php

declare(strict_types=1);

it('switches the contact page to English with the language links', function () {
    visit('/kontakt')
        ->click('a[hreflang="en-CH"]:visible')
        ->assertPathIs('/contact')
        ->assertSeeIn('h1:visible', 'Contact')
        ->assertNoJavaScriptErrors();
});

it('opens the mobile menu and navigates to the contact page', function () {
    visit('/de-ch')
        ->on()->mobile()
        ->assertMissing('#mobile-navigation')
        ->click('button[aria-controls="mobile-navigation"]')
        ->assertVisible('#mobile-navigation')
        ->assertAttribute('button[aria-controls="mobile-navigation"]', 'aria-expanded', 'true')
        ->click('#mobile-navigation a[href$="/kontakt"]')
        ->assertPathIs('/kontakt')
        ->assertNoJavaScriptErrors();
});

it('closes the mobile menu with Escape', function () {
    visit('/de-ch')
        ->on()->mobile()
        ->click('button[aria-controls="mobile-navigation"]')
        ->assertVisible('#mobile-navigation')
        ->keys('#mobile-navigation', 'Escape')
        ->assertMissing('#mobile-navigation')
        ->assertNoJavaScriptErrors();
});

it('suggests English to an English speaking visitor who arrives through the domain root', function () {
    visit('/')
        ->withLocale('en-US')
        ->assertPathIs('/de-ch')
        ->assertSee('This page is also available in English.')
        ->click('Switch to English')
        ->assertPathIs('/en-ch')
        ->assertSeeIn('h1:visible', 'Good to have you here')
        ->assertNoJavaScriptErrors();
});

it('suggests no other language to a German speaking visitor', function () {
    visit('/')
        ->withLocale('de-CH')
        ->assertPathIs('/de-ch')
        ->assertDontSee('This page is also available in English.')
        ->assertNoJavaScriptErrors();
});
