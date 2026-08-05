<?php

declare(strict_types=1);

return [

    'nav' => [
        'start' => 'Home',
        'scanning' => 'Digitization',
        'about' => 'About us',
        'contact' => 'Contact',
        'home' => 'Home',
        'media' => 'Media',
        'imprint' => 'Imprint',
        'privacy' => 'Privacy',
        'open_menu' => 'Open main menu',
        'close_menu' => 'Close menu',
    ],

    'seo' => [
        'start' => [
            'title' => 'Document scanning and digitization',
            'description' => 'We scan and digitize your documents in Zunzgen: free up space, archive audit-proof, search the full text.',
        ],
        'about' => [
            'title' => 'About us: scanning centre in Zunzgen',
            'description' => 'A scanning centre in Zunzgen, run by two companies from the region. Short distances, named contacts.',
        ],
        'scanning' => [
            'title' => 'Document scanning: prices per binder',
            'description' => 'Transparent prices for ring binders, loose documents and special formats – produced in north-western Switzerland.',
        ],
        'contact' => [
            'title' => 'Contact and locations',
            'description' => 'Two named contacts, reachable directly by e-mail and phone. Locations in Zunzgen and Oberwil.',
        ],
        'media' => [
            'title' => 'Media: logos and downloads',
            'description' => 'Logos and downloads around zunscan.ch – for press, partners and publications.',
        ],
        'imprint' => [
            'title' => 'Imprint',
            'description' => 'Imprint of paperflakes AG, Hauptstrasse 91, 4455 Zunzgen. Details on liability, links and copyright.',
        ],
        'privacy' => [
            'title' => 'Privacy',
            'description' => 'How we handle your data: confidentially, only with your consent, and transmitted over SSL.',
        ],
    ],

    'start' => [
        'hero_title' => 'The scanning centre in north-western Switzerland',
        'subtitle' => 'We create space and order in your office',
        'solutions_eyebrow' => 'What we offer',
        'solutions_title' => 'Here is what we can do for you',
        'space_title' => 'Free up space',
        'space_body' => 'We scan your documents and you lose the folders. The space that shelves and archive rooms take up today goes back to being room to work in.',
        // mail_*: currently not rendered (see zunscan/start/index.blade.php),
        // belongs to the disabled ePost service. Copy is kept in sync.
        'mail_title' => 'Post-scanning service',
        'mail_body' => 'We digitize your incoming physical mail and make it available to you online. That does not just boost your efficiency — it also gives you a seamless move from paper to digital workflows.',
        'compliance_title' => 'Digitization with audit-proof compliance',
        'compliance_body' => 'Every employee has secure access, anytime and from anywhere. We archive audit-proof, legally compliant and with a full audit trail.',
        'ocr_title' => 'Full-text search in seconds',
        'ocr_body' => 'Our full-text recognition (OCR) makes every page searchable. What used to mean leafing through an archive becomes a search of a few seconds.',
        'dms_title' => 'Setting up DMS & ECM',
        'dms_body' => 'We do not stop at scanning: on request we set up your document management (DMS/ECM), so the files land in a system that grows with you.',
        'pricing_title' => 'What does it cost to digitize your office?',
        'pricing_body' => 'Take a look at our price list.',
    ],

    'about' => [
        'title' => 'About us',
        'subtitle' => 'A scanning centre in Zunzgen, not an anonymous corporation',
        'work_eyebrow' => 'What we do',
        'what_we_do_title' => 'Paper in, searchable documents out',
        'lead' => 'We digitize paper — from a single ring binder to an archive built up over decades. All of it happens here in Zunzgen: your documents never leave north-western Switzerland.',
        'what_title' => 'Preparing and scanning',
        'what_body' => 'We prepare your documents by hand, removing staples, tabs and sleeves, scan them, and run full-text recognition over the result. You get back files in which you find in seconds what used to mean leafing through folders.',
        'how_title' => 'Working traceably',
        'how_body' => 'We document every step, so the digitization stays audit-proof and legally compliant. On request we then destroy the originals with a certificate — or you get them back untouched.',
        'who_eyebrow' => 'Who is behind zunscan.ch',
        'who_title' => 'Two companies from the region',
        'who_lead' => 'zunscan.ch is run by paperflakes AG. Behind it stand two companies that complement each other — and at both of them you talk to a named contact directly.',
        'partners' => [
            'real-estate-club' => 'Takes care of everything on site in Zunzgen: premises, logistics and short distances to customers in the region.',
            'codebar' => 'Takes care of the technology behind it: text recognition, clean processes and document management that holds up.',
        ],
    ],

    'contact' => [
        'title' => 'Contact',
        'subtitle' => 'Happy to help, digitally and in person',
        'eyebrow' => 'Two companies, two contacts',
        'heading' => 'A joint venture with short distances',
        'body' => 'zunscan.ch is run by paperflakes AG — a joint venture of codebar Solutions AG and Real Estate Club GmbH. You reach a named contact at either partner directly, with no contact form in between.',
        'locations_title' => 'Locations',
        'write_to' => 'Send an e-mail to :name',
        'call' => 'Call :name',
        'linkedin_of' => 'LinkedIn profile of :name',
    ],

    'media' => [
        'title' => 'Media',
        'subtitle' => 'Logos, news and coverage about zunscan.ch',
        'logo_alt' => 'zunscan.ch logo',
        'download' => 'Download logo',
    ],

    'legal' => [
        'imprint_title' => 'Imprint',
        'imprint_subtitle' => 'Who operates this website',
        'privacy_title' => 'Privacy',
        'privacy_subtitle' => 'What happens to your data',
        'company_title' => 'Company',
        'uid' => 'VAT ID',
        'email' => 'E-mail',
    ],

    'common' => [
        'price' => 'Price',
        'disposal' => 'Disposal',
        'service' => 'Service',
        'preparation' => 'Preparation',
        'remove_staples' => 'Removing staples',
        'remove_paperclips' => 'Removing paperclips',
        'remove_tabs' => 'Removing tabs',
        'remove_sleeves' => 'Removing plastic sleeves',
        'digitization_ocr' => 'Digitization incl. OCR',
    ],

    'services' => [
        'scanning' => [
            'title' => 'Digitization',
            'subtitle' => 'We turn paper into pixels and folders into bytes using top-of-the-line equipment',
            'prices_eyebrow' => 'Transparent pricing',
            'prices_title' => 'Prices',
            'vat_note' => 'All prices in CHF, excl. 8.1 % VAT.',

            'trial_title' => 'Free trial scan',
            'trial_body' => 'We digitize one binder free of charge. You see the result before you decide.',
            'trial_cta' => 'Request a trial scan',

            'folder_a4_title' => 'Price per A4 ring binder',
            'folder_a4_pages' => 'Up to 430 pages',
            'tier_quantity' => 'Quantity',
            'tier_price' => 'Price per binder',
            'tier_1_qty' => '1–19 binders',
            'tier_1_price' => 'CHF 64.50',
            'tier_2_qty' => '20–99 binders',
            'tier_2_price' => 'CHF 61.25',
            'tier_3_qty' => '100–499 binders',
            'tier_3_price' => 'CHF 58.25',
            'tier_4_qty' => '500 binders and up',
            'tier_4_price' => 'CHF 55.75',
            'tier_note' => 'The tiers apply per order. The total number of binders handed over is what counts.',
            'tier_open' => 'View volume pricing',
            'tier_dialog_title' => 'Volume pricing',
            'close' => 'Close',

            'page_a3a4_title' => 'Price per A3/A4 page – loose documents',
            'page_a3a4_price' => 'CHF 0.22',

            'setup_title' => 'Setup package',
            'setup_subtitle' => 'one-off, with your first order',
            'setup_price' => 'CHF 499.00',
            'setup_includes' => 'Included',
            'setup_item_pickup' => 'Collection of your documents',
            'setup_item_trial' => 'Free trial scan',
            'setup_item_structure' => 'Setting up the dossier and filing structure',
            'setup_note' => 'From 100 binders the setup package is credited in full against the order.',

            'more_services_title' => 'More services',

            'disposal_title' => 'Disposal',
            'disposal_price' => 'CHF 2.50 per binder',
            'disposal_subtitle' => 'You receive a destruction log and a certificate from Reisswolf Aktenvernichtung AG.',

            'return_title' => 'Return shipping',
            'return_loose_label' => 'Loose in a box',
            'return_loose_price' => 'CHF 25.00 per binder',
            'return_loose_body' => 'Your documents come back sorted, but unbound, in a box.',
            'return_original_label' => 'Reconstructed as received',
            'return_original_price' => 'CHF 108.50 per binder',
            'return_original_body' => 'Your files are bound back into the condition they arrived in.',
            'return_page_label' => 'A3/A4 page',
            'return_page_price' => 'CHF 0.31',

            'hourly_title' => 'Special formats and additional work',
            'hourly_production_label' => 'Production and special formats',
            'hourly_production_price' => 'CHF 95.00 / h',
            'hourly_dms_label' => 'DMS/ECM consulting and setup',
            'hourly_dms_price' => 'CHF 205.00 / h',

        ],
    ],

    'cta' => [
        'heading' => 'Get in touch for a tailored offer',
        'button' => 'Contact us',
    ],

    'footer' => [
        'heading' => 'Footer',
        'nav_title' => 'Navigation',
        'legal_title' => 'Legal',
        'copyright' => '© :year Zunscan. A project of :companies.',
        'swiss_digital_services' => 'swiss digital services',
    ],

];
