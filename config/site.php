<?php

return [
    'key' => env('SITE_KEY', 'codebar'),

    'company' => env('SITE_COMPANY', 'codebar Solutions AG'),

    'company_primary_color' => env('SITE_PRIMARY_COLOR', '#500472'),

    'sections' => [
        'news' => env('SITE_SECTION_NEWS', true),
        'products' => env('SITE_SECTION_PRODUCTS', true),
        'services' => env('SITE_SECTION_SERVICES', true),
        'technologies' => env('SITE_SECTION_TECHNOLOGIES', true),
        'open_source' => env('SITE_SECTION_OPEN_SOURCE', true),
        'co_working' => env('SITE_SECTION_CO_WORKING', true),
    ],

    'contact' => [
        'email' => env('SITE_CONTACT_EMAIL', 'info@codebar.ch'),
        'phone' => env('SITE_CONTACT_PHONE', '+41 61 515 60 90'),
        'offices' => [
            [
                'kind' => 'headquarter',
                'street' => 'Hauptstrasse 91',
                'postal_code' => '4455',
                'city' => 'Zunzgen',
                'country' => 'CH',
                'lat' => 47.4644,
                'lng' => 7.8716,
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Hauptstrasse+91+4455+Zunzgen',
            ],
            [
                'kind' => 'branch_office',
                'street' => 'Langegasse 39',
                'postal_code' => '4104',
                'city' => 'Oberwil',
                'country' => 'CH',
                'lat' => 47.4683,
                'lng' => 7.5478,
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Langegasse+39+4104+Oberwil',
            ],
        ],
    ],

    'links' => [
        'linkedin' => env('SITE_LINKEDIN', 'https://www.linkedin.com/company/codebarag'),
        'github' => env('SITE_GITHUB', 'https://github.com/orgs/codebar-ag'),
    ],

    'component_intro' => [
        'de_CH' => "## Wer wir sind\n\nDas Team von codebar versteht es, innovative Ideen mit digitalen Hilfsmitteln zum Leben zu erwecken. Wir denken wirtschaftlich, arbeiten gerne konzeptionell und setzen auf offene Technologien und Standards. Das alles ermöglicht es uns, Software zu entwickeln, die sich an den Bedürfnissen der Nutzer:innen orientiert – und dir echten Mehrwert bietet.\n\n## Wie wir arbeiten\n\nAm Anfang hören wir dir zu. Denn um effiziente Software zu entwickeln, muss man zunächst im Detail verstehen, wofür sie gedacht ist. Anschliessend erarbeiten wir gemeinsam ein Konzept, das sich an den Anforderungen der künftigen Nutzer:innen orientiert. Fällt der definierte Lösungsansatz in unseren Kompetenzbereich, unterstützen wir dich gerne auch bei der Realisierung. Andernfalls freuen wir uns, wenn andere unsere Pläne in die Tat umsetzen.\n",

        'en_CH' => "## Who we are\n\nThe team at codebar knows how to bring innovative ideas to life using digital tools. We think economically, enjoy working conceptually, and rely on open technologies and standards. This allows us to develop software that's built around the needs of its users – and delivers real value to you.\n\n## How we work\n\nWe start by listening. Because to develop effective software, you first need to understand exactly what it's meant to do. Next, we work with you to develop a concept based on the needs of future users. If the proposed solution fits our area of expertise, we're happy to support you through implementation as well. If not, we're just as pleased when others turn our plans into reality.\n",
    ],
];
