<?php

use App\Models\Contact;
use App\Models\News;
use App\Models\NewsSeries;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\get;

it('resolves the slug of the requested language', function () {
    $news = News::factory()->create([
        'slug' => ['de_CH' => 'die-deutsche-fassung', 'en_CH' => 'the-english-one'],
    ]);

    get(route('de-ch.news.show', ['locale' => 'de_CH', 'news' => 'die-deutsche-fassung']))->assertOk();
    get(route('en-ch.news.show', ['locale' => 'en_CH', 'news' => 'the-english-one']))->assertOk();

    expect($news->getTranslation('slug', 'de_CH'))->toBe('die-deutsche-fassung');
})->group('news');

it('hides an unpublished article behind a 404', function () {
    // The route binding resolves by slug and knows nothing about publication
    // state, so a draft used to be readable by anyone who guessed the URL.
    $news = News::factory()->unpublished()->create();

    get(route('de-ch.news.show', [
        'locale' => 'de_CH',
        'news' => $news->getTranslation('slug', 'de_CH'),
    ]))->assertNotFound();
})->group('news');

it('keeps an unpublished article off the index', function () {
    $published = News::factory()->create(['published_at' => now()->subDay()]);
    $draft = News::factory()->unpublished()->create();

    $html = (string) get(route('de-ch.news.index'))->assertOk()->getContent();

    expect($html)->toContain($published->getTranslation('title', 'de_CH'));
    expect($html)->not->toContain($draft->getTranslation('title', 'de_CH'));
})->group('news');

it('forgets the cached news list when an article is saved', function () {
    // Without this the sitemap showed a new article immediately while /aktuelles
    // kept serving the old list from a rememberForever cache.
    get(route('de-ch.news.index'))->assertOk();

    expect(Cache::has('news-published-de-ch'))->toBeTrue();

    News::factory()->create();

    expect(Cache::has('news-published-de-ch'))->toBeFalse();
})->group('news');

it('shows the author from the linked contact', function () {
    $contact = Contact::factory()->create([
        'name' => 'Testperson Muster',
        'sections' => ['employees' => ['key' => 'employees', 'role' => ['de_CH' => 'Testrolle', 'en_CH' => 'Test role']]],
    ]);

    $news = News::factory()->create(['contact_id' => $contact->id, 'author' => 'Alter Freitext']);

    $html = (string) get(route('de-ch.news.show', [
        'locale' => 'de_CH',
        'news' => $news->getTranslation('slug', 'de_CH'),
    ]))->assertOk()->getContent();

    expect($html)->toContain('Testperson Muster')->toContain('Testrolle');
    expect($html)->not->toContain('Alter Freitext');
})->group('news');

it('navigates between the parts of a series', function () {
    $series = NewsSeries::create([
        'key' => 'test-serie',
        'title' => ['de_CH' => 'Testserie', 'en_CH' => 'Test series'],
        'slug' => ['de_CH' => 'testserie', 'en_CH' => 'test-series'],
    ]);

    $makePart = fn (int $position): News => News::factory()->create([
        'series_id' => $series->id,
        'series_position' => $position,
        'published_at' => now()->subDays(10 - $position),
    ]);

    $first = $makePart(1);
    $middle = $makePart(2);
    $last = $makePart(3);

    $html = (string) get(route('de-ch.news.show', [
        'locale' => 'de_CH',
        'news' => $middle->getTranslation('slug', 'de_CH'),
    ]))->assertOk()->getContent();

    expect($html)
        ->toContain('Testserie')
        ->toContain($first->getTranslation('title', 'de_CH'))
        ->toContain($last->getTranslation('title', 'de_CH'));
})->group('news');

it('marks the article body with the reading layer', function () {
    $news = News::factory()->create();

    get(route('de-ch.news.show', [
        'locale' => 'de_CH',
        'news' => $news->getTranslation('slug', 'de_CH'),
    ]))->assertOk()->assertSee('id="article-body"', false);
})->group('news');
