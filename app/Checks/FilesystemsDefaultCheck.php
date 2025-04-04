<?php

namespace App\Checks;

use App\Enums\CacheKeyEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class FilesystemsDefaultCheck extends Check
{
    public function run(): Result
    {
        $defaultDisk = Config::get('filesystems.default');
        $fallbackDisk = Config::get('filesystems.default_fallback');

        /** @var string $filesystemsDefault */
        $filesystemsDefault = Cache::get(CacheKeyEnum::VALID_FILESYSTEMS_DEFAULT, $defaultDisk);

        $result = Result::make();

        $result->shortSummary("invalid filesystems default: {$filesystemsDefault}");

        if ($defaultDisk === $fallbackDisk) {
            return $result->failed();
        }

        return $result->ok();
    }
}
