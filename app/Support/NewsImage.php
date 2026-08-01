<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Resolves an image reference from an article's front matter or a :::figure directive.
 *
 * Three forms are accepted, so local placeholders keep working before assets are uploaded:
 *   - a full https:// URL (Cloudinary URLs get their transformation rewritten)
 *   - a Cloudinary public ID ("www-codebar-ch/news/foo/hero")
 *   - a path inside public/ ("images/news/foo.jpg" or "/images/news/foo.jpg")
 *
 * Editorial images are width-constrained only (c_limit) — unlike avatars, they must keep
 * their own aspect ratio rather than being cropped square.
 */
class NewsImage
{
    private const string CLOUDINARY_HOST = 'res.cloudinary.com';

    /** @var array<int, int> */
    public const array WIDTHS = [640, 960, 1280, 1920];

    public static function src(?string $reference, int $width): ?string
    {
        if ($reference === null || trim($reference) === '') {
            return null;
        }

        $reference = trim($reference);

        if (self::isLocalPath($reference)) {
            return asset(ltrim($reference, '/'));
        }

        if (str_starts_with($reference, 'http://') || str_starts_with($reference, 'https://')) {
            return str_contains($reference, self::CLOUDINARY_HOST)
                ? self::rewriteCloudinaryUrl($reference, $width)
                : $reference;
        }

        return self::fromPublicId($reference, $width);
    }

    /**
     * The og:image counterpart of an SVG hero. Social crawlers cannot render
     * SVG, so a same-named PNG rendered from it — see public/images/news/ and
     * public/images/services/ — is used when one exists; otherwise the caller
     * falls back to the site default image.
     */
    public static function ogImage(string $svgReference): ?string
    {
        if (! self::isLocalPath($svgReference) || ! str_ends_with(strtolower($svgReference), '.svg')) {
            return null;
        }

        $png = substr($svgReference, 0, -4).'.png';

        return is_file(public_path(ltrim($png, '/'))) ? asset(ltrim($png, '/')) : null;
    }

    public static function srcset(?string $reference, int $maxWidth): ?string
    {
        if (self::src($reference, $maxWidth) === null) {
            return null;
        }

        $widths = array_values(array_filter(self::WIDTHS, fn (int $w): bool => $w <= $maxWidth));

        if ($widths === []) {
            $widths = [$maxWidth];
        }

        $entries = [];

        foreach ($widths as $width) {
            $url = self::src($reference, $width);

            if ($url !== null) {
                $entries[] = $url.' '.$width.'w';
            }
        }

        return $entries === [] ? null : implode(', ', $entries);
    }

    private static function isLocalPath(string $reference): bool
    {
        return str_starts_with($reference, '/')
            || str_starts_with($reference, 'images/')
            || str_starts_with($reference, 'storage/');
    }

    private static function transformation(int $width): string
    {
        return "w_{$width},c_limit,f_auto,q_auto";
    }

    private static function fromPublicId(string $publicId, int $width): ?string
    {
        $cloud = config('filesystems.disks.cloudinary.cloud_name');

        if (! is_string($cloud) || $cloud === '') {
            return null;
        }

        return 'https://'.self::CLOUDINARY_HOST.'/'.$cloud.'/image/upload/'
            .self::transformation($width).'/'.ltrim($publicId, '/');
    }

    private static function rewriteCloudinaryUrl(string $url, int $width): string
    {
        $marker = '/image/upload/';
        $position = strpos($url, $marker);

        if ($position === false) {
            return $url;
        }

        $base = substr($url, 0, $position + strlen($marker));
        $rest = substr($url, $position + strlen($marker));
        $segments = explode('/', $rest);

        // Drop an existing transformation segment so ours does not stack on top of it.
        if (preg_match('/^[a-z0-9_,.:-]+$/', $segments[0]) === 1 && preg_match('/[whcfqe]_/', $segments[0]) === 1) {
            array_shift($segments);
        }

        return $base.self::transformation($width).'/'.implode('/', $segments);
    }
}
