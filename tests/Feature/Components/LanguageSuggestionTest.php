<?php

declare(strict_types=1);

use App\Enums\CookieNameEnum;
use App\Enums\LocaleEnum;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

function startPageMarkup(string $locale): string
{
    $response = get(route(Str::slug($locale).'.start.index'));
    $response->assertOk();

    return (string) $response->getContent();
}

function suggestionMarkup(string $locale): string
{
    return Str::of(startPageMarkup($locale))
        ->after('x-data="languageSuggestion"')
        ->before('<section')
        ->toString();
}

function suggestionCopy(string $key, string $locale): string
{
    $text = __('components.language_suggestion.'.$key, [], $locale);

    return is_string($text) ? $text : throw new RuntimeException("Missing copy: {$key} ({$locale})");
}

it('ships the suggestion on the start page of every locale', function (string $locale) {
    expect(startPageMarkup($locale))->toContain('x-data="languageSuggestion"');
})->with([[LocaleEnum::DE->value], [LocaleEnum::EN->value]])->group('language-suggestion');

it('hands the component the cookie the entry redirect leaves behind', function () {
    expect(suggestionMarkup(LocaleEnum::DE->value))
        ->toContain('data-cookie="'.CookieNameEnum::ENTRY_REDIRECT->value.'"');
})->group('language-suggestion');

it('names the page language and the fallback language', function () {
    expect(suggestionMarkup(LocaleEnum::DE->value))
        ->toContain('data-language="de"')
        ->toContain('data-fallback="en"');
})->group('language-suggestion');

it('keeps the same fallback language on the English page', function () {
    expect(suggestionMarkup(LocaleEnum::EN->value))
        ->toContain('data-language="en"')
        ->toContain('data-fallback="en"');
})->group('language-suggestion');

it('offers the English page from the German one', function () {
    $markup = suggestionMarkup(LocaleEnum::DE->value);

    expect($markup)->toContain('data-language="en"');
    expect($markup)->toContain(route(Str::slug(LocaleEnum::EN->value).'.start.index'));
})->group('language-suggestion');

it('offers the German page from the English one', function () {
    $markup = suggestionMarkup(LocaleEnum::EN->value);

    expect($markup)->toContain('data-language="de"');
    expect($markup)->toContain(route(Str::slug(LocaleEnum::DE->value).'.start.index'));
})->group('language-suggestion');

it('offers nothing but the other locale', function () {
    $markup = suggestionMarkup(LocaleEnum::DE->value);

    expect(substr_count($markup, '<div data-language="'))->toBe(1);
    expect($markup)->toContain('<div data-language="en"');
})->group('language-suggestion');

it('writes the offer in the language it is offering, so its reader can read it', function () {
    $german = suggestionMarkup(LocaleEnum::DE->value);

    expect($german)->toContain(suggestionCopy('message', LocaleEnum::EN->value));
    expect($german)->toContain(suggestionCopy('action', LocaleEnum::EN->value));
    expect($german)->not->toContain(suggestionCopy('message', LocaleEnum::DE->value));

    $english = suggestionMarkup(LocaleEnum::EN->value);

    expect($english)->toContain(suggestionCopy('message', LocaleEnum::DE->value));
    expect($english)->not->toContain(suggestionCopy('message', LocaleEnum::EN->value));
})->group('language-suggestion');

it('marks the offer with the language it is written in', function () {
    expect(suggestionMarkup(LocaleEnum::DE->value))
        ->toContain('lang="en-CH"')
        ->toContain('hreflang="en-CH"');
})->group('language-suggestion');

it('renders hidden, because the cached page cannot know who is reading it', function () {
    $markup = suggestionMarkup(LocaleEnum::DE->value);

    expect(Str::before($markup, 'data-language="en"'))->toContain('hidden');
    expect(Str::after($markup, 'data-language="en"'))->toContain('hidden');
})->group('language-suggestion');

it('gives the dismiss control an accessible name', function () {
    expect(suggestionMarkup(LocaleEnum::DE->value))
        ->toContain('x-on:click="dismiss"')
        ->toContain(suggestionCopy('dismiss', LocaleEnum::EN->value));
})->group('language-suggestion');

it('stays off pages the entry redirect never lands on', function (string $route) {
    $response = get(route(Str::slug(LocaleEnum::DE->value).'.'.$route));
    $response->assertOk();

    expect((string) $response->getContent())->not->toContain('x-data="languageSuggestion"');
})->with([['about-us.index'], ['contact.index'], ['news.index']])->group('language-suggestion');
