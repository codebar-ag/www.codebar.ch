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
            'title' => 'Welcome',
            'description' => 'zunscan.ch - your scanning centre: free up office space, digitize incoming mail, search full text in seconds. Start into a more efficient future today!',
        ],
        'about' => [
            'title' => 'About us',
            'description' => 'Start your journey into the future of document management with us. Free up space, digitize incoming mail, and search full text. Local, from 4455 Zunzgen.',
        ],
        'scanning' => [
            'title' => 'Digitization',
            'description' => 'Document scanning at unbeatable prices: A4 and A3 pages, plans, books, ring binders and more. zunscan.ch - your partner for digital solutions!',
        ],
        'contact' => [
            'title' => 'Contact',
            'description' => 'Two contacts, one partner: zunscan.ch is run by paperflakes AG, a joint venture of codebar Solutions AG and Real Estate Club GmbH.',
        ],
        'media' => [
            'title' => 'Media',
            'description' => 'Logos, press coverage and news about scanning, document management and OCR. Stay up to date with zunscan.ch!',
        ],
        'imprint' => [
            'title' => 'Imprint',
            'description' => 'No warranty for the accuracy of content, no liability for links. Copyright: all rights reserved by paperflakes AG. Written consent required for reproduction.',
        ],
        'privacy' => [
            'title' => 'Privacy',
            'description' => 'Privacy in line with the GDPR: we treat your data confidentially and only process it with your consent. SSL encryption protects your data in transit. Consent can be withdrawn at any time.',
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
            'folder_a4_title' => 'Price per A4 ring binder',
            'folder_a4_pages' => 'Up to 430 pages',
            'folder_a4_price' => 'CHF 64.50',
            'folder_a4_disposal' => 'CHF 1.50 / kg',
            'page_a3a4_title' => 'Price per A3/A4 page',
            'page_a3a4_price' => 'CHF 0.19',
            'page_a3a4_disposal' => 'CHF 1.50 / kg',
            'flat_fee_title' => 'Order flat fee',
            'flat_fee_price' => 'CHF 499.00 for your first order',
            'more_services_title' => 'More services',
            'disposal_title' => 'Disposal',
            'disposal_subtitle' => 'You receive a destruction certificate from Reisswolf Aktenvernichtung AG.',
            'disposal_price_label' => 'Per destruction order',
            'disposal_price' => 'CHF 250.00',
            'return_title' => 'Return shipping',
            'return_subtitle' => 'You get your files back in their original condition.',
            'return_folder_label' => 'A4 folder',
            'return_folder_price' => 'CHF 108.50',
            'return_page_label' => 'A3/A4 page',
            'return_page_price' => 'CHF 0.31',
            'other_title' => 'Anything else',
            'other_subtitle' => 'Got a different format you would like digitized?',
            'other_price_label' => 'Billed by effort',
            'other_price' => 'CHF 90.00 / h',
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
