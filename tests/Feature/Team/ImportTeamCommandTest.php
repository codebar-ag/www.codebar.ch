<?php

use App\Models\Contact;
use Illuminate\Support\Facades\File;

use function Pest\Laravel\get;

/**
 * Keeps track of the throwaway directories a test wrote to, so afterEach can
 * remove them without leaning on undeclared $this properties.
 */
class TeamTempDirectories
{
    /** @var array<int, string> */
    private static array $paths = [];

    public static function next(): string
    {
        $path = sys_get_temp_dir().'/team-import-'.bin2hex(random_bytes(4));
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
 * @param  array<string, string>  $files  filename => yaml
 */
function writeTeamFiles(array $files): string
{
    $base = TeamTempDirectories::next();
    File::ensureDirectoryExists($base);

    foreach ($files as $name => $contents) {
        File::put($base.'/'.$name, $contents);
    }

    return $base;
}

function personYaml(string $key, string $name, bool $published = true, int $sort = 1): string
{
    $flag = $published ? 'true' : 'false';

    return <<<YAML
        key: {$key}
        name: {$name}
        published: {$flag}
        sort: {$sort}
        image: https://example.test/{$key}.webp
        sections:
          employees:
            role:
              de_CH: Testrolle
              en_CH: Test role
        icons:
          email: {$key}@codebar.ch
          linkedin: https://www.linkedin.com/in/{$key}/
        YAML;
}

afterEach(function () {
    TeamTempDirectories::cleanUp();
});

it('imports a person from a yaml file', function () {
    $base = writeTeamFiles(['a.yaml' => personYaml('test-person', 'Test Person')]);

    runArtisan('team:import', ['--path' => $base])->assertExitCode(0);

    $contact = Contact::where('key', 'test-person')->firstOrFail();

    expect($contact->name)->toBe('Test Person');
    expect($contact->published)->toBeTrue();
    expect($contact->sort)->toBe(1);

    // The section key is filled in by the importer so the YAML need not repeat it.
    expect(data_get($contact->sections, 'employees.key'))->toBe('employees');
    expect(data_get($contact->sections, 'employees.role.de_CH'))->toBe('Testrolle');
    expect(data_get($contact->icons, 'email'))->toBe('test-person@codebar.ch');
})->group('team', 'console');

it('can be run repeatedly without creating duplicates', function () {
    $base = writeTeamFiles(['a.yaml' => personYaml('test-person', 'Test Person')]);

    runArtisan('team:import', ['--path' => $base])->assertExitCode(0);
    runArtisan('team:import', ['--path' => $base])->assertExitCode(0);

    expect(Contact::where('key', 'test-person')->count())->toBe(1);
})->group('team', 'console');

it('removes a person whose file is gone', function () {
    // The files are the source of truth — deleting one has to take the person off
    // the site, otherwise the database quietly keeps someone who left.
    $base = writeTeamFiles([
        'a.yaml' => personYaml('stays', 'Stays Here'),
        'b.yaml' => personYaml('leaves', 'Leaves Soon', sort: 2),
    ]);

    runArtisan('team:import', ['--path' => $base])->assertExitCode(0);
    expect(Contact::count())->toBe(2);

    File::delete($base.'/b.yaml');

    runArtisan('team:import', ['--path' => $base])->assertExitCode(0);

    expect(Contact::count())->toBe(1);
    expect(Contact::where('key', 'leaves')->exists())->toBeFalse();
})->group('team', 'console');

it('writes nothing on a dry run', function () {
    $base = writeTeamFiles(['a.yaml' => personYaml('test-person', 'Test Person')]);

    runArtisan('team:import', ['--path' => $base, '--dry-run' => true])->assertExitCode(0);

    expect(Contact::where('key', 'test-person')->exists())->toBeFalse();
})->group('team', 'console');

it('reports a file without a name instead of importing it', function () {
    $base = writeTeamFiles(['a.yaml' => "key: broken\npublished: true\n"]);

    runArtisan('team:import', ['--path' => $base])->assertExitCode(1);

    expect(Contact::where('key', 'broken')->exists())->toBeFalse();
})->group('team', 'console');

it('reports malformed yaml instead of failing', function () {
    $base = writeTeamFiles(['a.yaml' => "key: broken\n  name: [unclosed\n"]);

    runArtisan('team:import', ['--path' => $base])->assertExitCode(1);
})->group('team', 'console');

it('orders the team page by the sort field', function () {
    $base = writeTeamFiles([
        'a.yaml' => personYaml('zoe-last', 'Zoe Last', sort: 1),
        'b.yaml' => personYaml('adam-first', 'Adam First', sort: 2),
    ]);

    runArtisan('team:import', ['--path' => $base])->assertExitCode(0);

    // Alphabetically Adam would come first; the sort field must win.
    get(route('de-ch.about-us.index'))
        ->assertOk()
        ->assertSeeInOrder(['Zoe Last', 'Adam First']);
})->group('team', 'console');
