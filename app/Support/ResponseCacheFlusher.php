<?php

declare(strict_types=1);

namespace App\Support;

use Spatie\ResponseCache\Facades\ResponseCache;

/**
 * Clearing the rendered pages is all-or-nothing — there is no per-URL invalidation —
 * so a single save clears the whole site. That is the right trade for one edit and the
 * wrong one for an import, which saves every record it reads and would otherwise clear
 * the site once per row.
 *
 * batch() collapses that to a single clear at the end.
 */
class ResponseCacheFlusher
{
    private static bool $batching = false;

    private static bool $pending = false;

    public static function flush(): void
    {
        if (self::$batching) {
            self::$pending = true;

            return;
        }

        ResponseCache::clear();
    }

    /**
     * @template TReturn
     *
     * @param  callable(): TReturn  $callback
     * @return TReturn
     */
    public static function batch(callable $callback): mixed
    {
        $wasBatching = self::$batching;
        self::$batching = true;

        try {
            return $callback();
        } finally {
            self::$batching = $wasBatching;

            // A half-finished import still changed rows, so the flush owes nothing to
            // the callback having succeeded.
            if (! self::$batching && self::$pending) {
                self::$pending = false;

                ResponseCache::clear();
            }
        }
    }
}
