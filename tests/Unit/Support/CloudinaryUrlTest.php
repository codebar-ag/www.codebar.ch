<?php

declare(strict_types=1);

use App\Support\CloudinaryUrl;

it('transforms cloudinary urls with sizing parameters', function () {
    $url = 'https://res.cloudinary.com/codebar/image/upload/team/Alex_v3.webp';

    expect(CloudinaryUrl::src($url, 256))->toBe(
        'https://res.cloudinary.com/codebar/image/upload/w_256,h_256,c_fill,f_auto,q_auto/team/Alex_v3.webp'
    );
});

it('replaces existing cloudinary transforms', function () {
    $url = 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-codebar-ch/people/alexander_boll.webp';

    expect(CloudinaryUrl::src($url, 256))->toBe(
        'https://res.cloudinary.com/codebar/image/upload/w_256,h_256,c_fill,f_auto,q_auto/www-codebar-ch/people/alexander_boll.webp'
    );
});

it('returns non-cloudinary urls unchanged', function () {
    $url = 'https://www.codebar.ch/images/team/alex.webp';

    expect(CloudinaryUrl::src($url, 256))->toBe($url);
});

it('builds a responsive srcset', function () {
    $url = 'https://res.cloudinary.com/codebar/image/upload/team/Alex_v3.webp';

    expect(CloudinaryUrl::srcset($url, 256))->toBe(
        'https://res.cloudinary.com/codebar/image/upload/w_256,h_256,c_fill,f_auto,q_auto/team/Alex_v3.webp 256w, '.
        'https://res.cloudinary.com/codebar/image/upload/w_512,h_512,c_fill,f_auto,q_auto/team/Alex_v3.webp 512w'
    );
});

it('transforms cloudinary urls to a width and a height', function () {
    $url = 'https://res.cloudinary.com/codebar/image/upload/w_900,h_300,c_fill,f_auto,q_auto/www-codebar-ch/network/pst-3x1-black.svg';

    expect(CloudinaryUrl::transform($url, 384, 128))->toBe(
        'https://res.cloudinary.com/codebar/image/upload/w_384,h_128,c_fill,f_auto,q_auto/www-codebar-ch/network/pst-3x1-black.svg'
    );
});
