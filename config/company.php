<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Company master data
|--------------------------------------------------------------------------
|
| Single source of truth for name, address and phone (NAP). Both the visible
| pages (contact, imprint) and the JSON-LD schema graph read from here, so the
| two can never drift apart — inconsistent NAP data is exactly what stops
| Google and AI answer engines from resolving us as one entity.
|
| Keep this in sync with the Zefix entry, the Google Business Profiles and the
| LinkedIn company page. If a value changes here, it changes everywhere.
|
*/

return [
    'legal_name' => 'codebar Solutions AG',

    /*
     * Names people actually search for. Feeds schema.org alternateName, which
     * is how Google learns that "codebar Solutions" and "codebar Solutions AG"
     * are the same entity.
     */
    'alternate_names' => [
        'codebar Solutions',
        'codebar',
    ],

    'legal_form' => 'Aktiengesellschaft (AG)',

    /* Swiss business identification number (UID/CHE), as shown in the imprint. */
    'uid' => 'CHE-257.955.682',
    'zefix_url' => 'https://zefix.ch/de/search/entity/list/firm/1466584',

    'email' => 'info@codebar.ch',

    /*
     * 'e164' is the machine-readable form for schema.org and tel: links,
     * 'display' is what a human sees on the page.
     */
    'phone' => [
        'e164' => '+41615156090',
        'display' => '+41 61 515 60 90',
    ],

    'logo' => 'images/logos/codebar-logo-colored.png',

    /*
     * Verified profiles on other platforms. schema.org sameAs — this is the
     * anchor set search engines and LLMs use to tie the website to the company
     * as a known entity.
     *
     * Only ever add URLs that genuinely belong to us and resolve. An invented
     * or dead profile is worse than a short list.
     */
    'same_as' => [
        'https://www.linkedin.com/company/codebar-solutions-ag',
        'https://github.com/codebar-ag',
        // TODO: Swiss Made Software / Swiss Digital Services profile pages.
    ],

    /*
     * Physical locations, in the order they appear on the contact page.
     * 'primary' marks the registered head office.
     *
     * 'geo' is intentionally absent: we have no surveyed coordinates, and a
     * guessed lat/lng is worse than none — Google geocodes the postal address
     * on its own. Add it only with values taken from the Business Profile.
     */
    'locations' => [
        [
            'key' => 'zunzgen',
            'primary' => true,
            'city' => 'Zunzgen',
            'label' => 'Headquarter',
            'street' => 'Hauptstrasse 91',
            'postal_code' => '4455',
            'country' => 'CH',
            'map_url' => 'https://maps.app.goo.gl/d9iK5vCrHHAHUcvx6',
        ],
        [
            'key' => 'oberwil',
            'primary' => false,
            'city' => 'Oberwil',
            'label' => 'Office',
            'street' => 'Langegasse 39',
            'postal_code' => '4104',
            'country' => 'CH',
            'map_url' => 'https://maps.app.goo.gl/1ndrUgUvw2pxxekUA',
        ],
    ],

    /*
     * Opening hours. 'open'/'close' null means closed that day.
     * Day keys are English weekday names — schema.org expects exactly these,
     * and the opening-hours component translates them for display.
     */
    'opening_hours' => [
        ['day' => 'Monday', 'open' => '08:00', 'close' => '18:00'],
        ['day' => 'Tuesday', 'open' => '08:00', 'close' => '18:00'],
        ['day' => 'Wednesday', 'open' => '08:00', 'close' => '18:00'],
        ['day' => 'Thursday', 'open' => '08:00', 'close' => '18:00'],
        ['day' => 'Friday', 'open' => '08:00', 'close' => '18:00'],
        ['day' => 'Saturday', 'open' => '08:00', 'close' => '12:00'],
        ['day' => 'Sunday', 'open' => null, 'close' => null],
    ],
];
