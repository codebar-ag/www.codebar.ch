<?php

namespace App\Support;

class CloudinaryUrl
{
    private const string CLOUDINARY_HOST = 'res.cloudinary.com';

    private const string UPLOAD_MARKER = '/image/upload/';

    public static function src(string $url, int $width): string
    {
        return self::transform($url, $width);
    }

    public static function srcset(string $url, int $width): string
    {
        $oneX = self::transform($url, $width);
        $twoX = self::transform($url, $width * 2);

        return "{$oneX} {$width}w, {$twoX} ".($width * 2).'w';
    }

    public static function transform(string $url, int $width): string
    {
        if (! str_contains($url, self::CLOUDINARY_HOST)) {
            return $url;
        }

        $markerPos = strpos($url, self::UPLOAD_MARKER);

        if ($markerPos === false) {
            return $url;
        }

        $base = substr($url, 0, $markerPos + strlen(self::UPLOAD_MARKER));
        $rest = substr($url, $markerPos + strlen(self::UPLOAD_MARKER));
        $publicId = self::stripExistingTransforms($rest);
        $transforms = "w_{$width},h_{$width},c_fill,f_auto,q_auto";

        return $base.$transforms.'/'.$publicId;
    }

    private static function stripExistingTransforms(string $path): string
    {
        $segments = explode('/', $path);

        if (
            preg_match('/^[a-z0-9_,.-]+$/', $segments[0])
            && preg_match('/[whcfq]_/', $segments[0])
        ) {
            array_shift($segments);
        }

        return implode('/', $segments);
    }
}
