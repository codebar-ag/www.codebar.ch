<?php

declare(strict_types=1);

return [

    // Production is zunscan.codebar.ch. Herd cannot resolve a real public TLD
    // locally (herd link always appends .test and custom TLDs aren't
    // supported), so local dev overrides this to whatever hostname the
    // Herd/Valet symlink actually produces, e.g. zunscan.web.codebar.test.
    'domain' => env('ZUNSCAN_DOMAIN', 'zunscan.codebar.ch'),

    // zunscan.ch is run by paperflakes AG, a joint venture of these two
    // companies — one contact person each. Config rather than the database:
    // this sub-site deliberately has no models, and none of these fields are
    // translated (the company name doubles as the role). Portraits are
    // Cloudinary URLs, which is the only remote image host the CSP allows.
    'people' => [
        [
            'key' => 'real-estate-club',
            'name' => 'Mischa Lanz',
            'company' => 'Real Estate Club GmbH',
            'email' => 'mischa.lanz@realestateclub.ch',
            'phone' => '+41 61 515 20 40',
            'website' => 'https://www.realestateclub.ch/',
            'website_label' => 'www.realestateclub.ch',
            'linkedin' => 'https://www.linkedin.com/in/mischa-lanz-672a65112/',
            'image' => 'https://res.cloudinary.com/codebar/image/upload/w_400,h_400,f_auto,q_auto/www-paperflakes-ch/people/6528f7c6bddf8430fb5d154c_Mischa_Hemd.webp',
        ],
        [
            'key' => 'codebar',
            'name' => 'Sebastian Bürgin-Fix',
            'company' => 'codebar Solutions AG',
            'email' => 'sebastian.buergin@codebar.ch',
            'phone' => '+41 61 515 60 95',
            'website' => 'https://www.codebar.ch/',
            'website_label' => 'www.codebar.ch',
            'linkedin' => 'https://www.linkedin.com/in/sebastian-buergin/',
            'image' => 'https://res.cloudinary.com/codebar/image/upload/e_background_removal/c_thumb,g_face,z_0.7,w_500,h_500,b_rgb:E4E6E9/f_auto,q_auto/www-codebar-ch/team/Sebastian_V3.webp',
        ],
    ],

    // One entry per address, not per company: codebar has two sites, and
    // stacking them inside a single card left the Real Estate Club card
    // half empty next to it.
    'locations' => [
        [
            'company' => 'Real Estate Club GmbH',
            'street' => 'Hauptstrasse 91',
            'city' => 'CH-4455 Zunzgen',
        ],
        [
            'company' => 'codebar Solutions AG',
            'street' => 'Langegasse 39',
            'city' => 'CH-4104 Oberwil',
        ],
    ],

    // paperflakes AG's registered address — the operating entity behind
    // zunscan.ch. Single source for the imprint and the JSON-LD graph.
    'company' => [
        'legal_name' => 'paperflakes AG',
        'street' => 'Hauptstrasse 91',
        'postal_code' => '4455',
        'locality' => 'Zunzgen',
        'uid' => 'CHE-432.585.498',
        'email' => 'info@paperflakes.ch',
    ],

];
