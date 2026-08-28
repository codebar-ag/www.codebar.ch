<?php

declare(strict_types=1);

use App\Support\NewsImage;

it('inserts a width-limited transformation into a cloudinary url', function () {
    $url = 'https://res.cloudinary.com/codebar/image/upload/www-codebar-ch/news/foo/hero.webp';

    expect(NewsImage::src($url, 960))->toBe(
        'https://res.cloudinary.com/codebar/image/upload/w_960,c_limit,f_auto,q_auto/www-codebar-ch/news/foo/hero.webp'
    );
})->group('unit', 'support');

it('replaces an existing cloudinary transformation instead of stacking on it', function () {
    $url = 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,c_fill,f_auto,q_auto/www-codebar-ch/news/foo/hero.webp';

    expect(NewsImage::src($url, 1280))->toBe(
        'https://res.cloudinary.com/codebar/image/upload/w_1280,c_limit,f_auto,q_auto/www-codebar-ch/news/foo/hero.webp'
    );
})->group('unit', 'support');

it('keeps a plain folder segment that carries no transformation keys', function () {
    $url = 'https://res.cloudinary.com/codebar/image/upload/news/hero.webp';

    expect(NewsImage::src($url, 640))->toBe(
        'https://res.cloudinary.com/codebar/image/upload/w_640,c_limit,f_auto,q_auto/news/hero.webp'
    );
})->group('unit', 'support');

it('returns a cloudinary url without an upload marker unchanged', function () {
    $url = 'https://res.cloudinary.com/codebar/raw/news/hero.webp';

    expect(NewsImage::src($url, 640))->toBe($url);
})->group('unit', 'support');

it('builds a srcset from every preset width up to the maximum', function () {
    $url = 'https://res.cloudinary.com/codebar/image/upload/news/hero.webp';

    expect(NewsImage::srcset($url, 1280))->toBe(
        'https://res.cloudinary.com/codebar/image/upload/w_640,c_limit,f_auto,q_auto/news/hero.webp 640w, '.
        'https://res.cloudinary.com/codebar/image/upload/w_960,c_limit,f_auto,q_auto/news/hero.webp 960w, '.
        'https://res.cloudinary.com/codebar/image/upload/w_1280,c_limit,f_auto,q_auto/news/hero.webp 1280w'
    );
})->group('unit', 'support');

it('falls back to the maximum width when it is below every preset width', function () {
    $url = 'https://res.cloudinary.com/codebar/image/upload/news/hero.webp';

    expect(NewsImage::srcset($url, 320))->toBe(
        'https://res.cloudinary.com/codebar/image/upload/w_320,c_limit,f_auto,q_auto/news/hero.webp 320w'
    );
})->group('unit', 'support');

it('returns no srcset for a missing reference', function () {
    expect(NewsImage::srcset(null, 1280))->toBeNull()
        ->and(NewsImage::srcset('   ', 1280))->toBeNull();
})->group('unit', 'support');

it('returns no srcset for a public id when the cloud name is not configured', function () {
    config()->set('filesystems.disks.cloudinary.cloud_name', null);

    expect(NewsImage::srcset('www-codebar-ch/news/foo/hero', 1280))->toBeNull();
})->group('unit', 'support');
