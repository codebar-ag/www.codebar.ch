<?php

declare(strict_types=1);

namespace Tests\Support;

use Illuminate\Support\Facades\File;

/**
 * Keeps track of the throwaway directories a test wrote to, so afterEach can
 * remove them without each test having to remember its own paths.
 *
 * Lives in its own PSR-4 file rather than inside a test file: a class declared
 * in a *Test.php file is skipped by the classmap generator, and composer says
 * so on every dump-autoload.
 */
class TempDirectories
{
    /** @var array<int, string> */
    private static array $paths = [];

    public static function next(string $prefix): string
    {
        $path = sys_get_temp_dir().'/'.$prefix.'-'.bin2hex(random_bytes(4));
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
