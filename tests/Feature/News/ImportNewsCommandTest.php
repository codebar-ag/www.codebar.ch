<?php

use App\Models\Contact;
use App\Models\News;
use App\Models\NewsSeries;
use App\Models\NewsTag;
use Illuminate\Support\Facades\File;

/**
 * Keeps track of the throwaway directories a test wrote to, so afterEach can
 * remove them without leaning on undeclared $this properties.
 */
class ImportTempDirectories
{
    /** @var array<int, string> */
    private static array $paths = [];

    public static function next(): string
    {
        $path = sys_get_temp_dir().'/news-import-'.bin2hex(random_bytes(4));
        self::$paths[] = $path;

        return $path;
    }

    public static function cleanUp(): void
    {
        foreach (self::$paths as $path) {
            File::deleteDirectory($path);
        }

        self::$paths = [];
    }
}

/**
 * @param  array<string, string>  $files  locale => file contents
 */
function writeArticles(array $files): string
{
    $base = ImportTempDirectories::next();

    foreach ($files as $locale => $contents) {
        File::ensureDirectoryExists($base.'/'.$locale);
        File::put($base.'/'.$locale.'/article.md', $contents);
    }

    return $base;
}

function articleFile(string $title, string $slug, string $extra = ''): string
{
    return <<<MD
        ---
        key: test-article
        slug: {$slug}
        title: {$title}
        teaser: Ein Teaser.
        published_at: 2026-05-01
        tags: [DMS/ECM, Migration]
        {$extra}
        ---

        ## Ein Abschnitt

        Etwas Fliesstext für die Lesezeit.
        MD;
}

afterEach(function () {
    ImportTempDirectories::cleanUp();
});

it('imports an article that exists in both languages', function () {
    $base = writeArticles([
        'de_CH' => articleFile('Deutscher Titel', 'deutscher-titel'),
        'en_CH' => articleFile('English title', 'english-title'),
    ]);

    runArtisan('news:import', ['--path' => $base])->assertExitCode(0);

    $news = News::where('key', 'test-article')->firstOrFail();

    expect($news->getTranslation('title', 'de_CH'))->toBe('Deutscher Titel')
        ->and($news->getTranslation('title', 'en_CH'))->toBe('English title')
        ->and($news->getTranslation('slug', 'de_CH'))->toBe('deutscher-titel')
        ->and($news->getTranslation('slug', 'en_CH'))->toBe('english-title')
        ->and($news->published_at?->toDateString())->toBe('2026-05-01')
        ->and($news->reading_minutes)->toBeGreaterThan(0);
})->group('news', 'console');

it('skips an article that is missing a translation', function () {
    // A half-translated pair would publish an hreflang link joining two
    // different articles, so it must never reach the database.
    $base = writeArticles(['de_CH' => articleFile('Nur Deutsch', 'nur-deutsch')]);

    runArtisan('news:import', ['--path' => $base])->assertExitCode(1);

    expect(News::where('key', 'test-article')->exists())->toBeFalse();
})->group('news', 'console');

it('can be run repeatedly without creating duplicates', function () {
    $base = writeArticles([
        'de_CH' => articleFile('Deutscher Titel', 'deutscher-titel'),
        'en_CH' => articleFile('English title', 'english-title'),
    ]);

    runArtisan('news:import', ['--path' => $base])->assertExitCode(0);
    runArtisan('news:import', ['--path' => $base])->assertExitCode(0);

    expect(News::where('key', 'test-article')->count())->toBe(1);
})->group('news', 'console');

it('writes nothing on a dry run', function () {
    $base = writeArticles([
        'de_CH' => articleFile('Deutscher Titel', 'deutscher-titel'),
        'en_CH' => articleFile('English title', 'english-title'),
    ]);

    runArtisan('news:import', ['--path' => $base, '--dry-run' => true])->assertExitCode(0);

    expect(News::where('key', 'test-article')->exists())->toBeFalse();
})->group('news', 'console');

it('creates tag rows and keeps the denormalised labels', function () {
    $base = writeArticles([
        'de_CH' => articleFile('Deutscher Titel', 'deutscher-titel'),
        'en_CH' => articleFile('English title', 'english-title'),
    ]);

    runArtisan('news:import', ['--path' => $base])->assertExitCode(0);

    $news = News::where('key', 'test-article')->firstOrFail();

    expect($news->tags)->toBe(['DMS/ECM', 'Migration'])
        // "DMS/ECM" must not collapse into "dmsecm".
        ->and($news->newsTags->pluck('key')->all())->toContain('dms-ecm')
        ->and(NewsTag::count())->toBe(2);
})->group('news', 'console');

it('links the author to a contact by email', function () {
    $contact = Contact::factory()->create([
        'name' => 'Testperson',
        'icons' => ['email' => 'test@codebar.ch'],
    ]);

    $base = writeArticles([
        'de_CH' => articleFile('Deutscher Titel', 'deutscher-titel', 'author: test@codebar.ch'),
        'en_CH' => articleFile('English title', 'english-title', 'author: test@codebar.ch'),
    ]);

    runArtisan('news:import', ['--path' => $base])->assertExitCode(0);

    expect(News::where('key', 'test-article')->firstOrFail()->contact_id)->toBe($contact->id);
})->group('news', 'console');

it('creates the series named in the front matter', function () {
    $extra = "series: dms-migration\nseries_position: 2\nseries_title: DMS-Migration";

    $base = writeArticles([
        'de_CH' => articleFile('Deutscher Titel', 'deutscher-titel', $extra),
        'en_CH' => articleFile('English title', 'english-title', $extra),
    ]);

    runArtisan('news:import', ['--path' => $base])->assertExitCode(0);

    $news = News::where('key', 'test-article')->firstOrFail();
    $series = NewsSeries::where('key', 'dms-migration')->firstOrFail();

    expect($news->series_id)->toBe($series->id)
        ->and($news->series_position)->toBe(2);
})->group('news', 'console');

it('reports a file without front matter instead of failing', function () {
    $base = writeArticles([
        'de_CH' => "Kein Front Matter, nur Text.\n",
        'en_CH' => articleFile('English title', 'english-title'),
    ]);

    runArtisan('news:import', ['--path' => $base])->assertExitCode(1);

    expect(News::count())->toBe(0);
})->group('news', 'console');
