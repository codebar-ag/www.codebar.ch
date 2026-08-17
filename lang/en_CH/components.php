<?php

declare(strict_types=1);

return [
    'intro' => [
        'title' => 'Good to have you here',
        'legend' => 'What would you like to know about us?',
        'shortcuts' => 'Switch section: number keys 1 to 3, or the left and right arrow keys.',
        'prev_section' => 'Previous section',
        'next_section' => 'Next section',
        'next' => 'next: :title',
        'expertise' => 'take a look at our expertise',
        'cta' => 'tell us about your project',
        'who_we_are' => [
            'title' => 'Who we are',
            'command' => 'who-we-are',
            'teaser' => 'Who we are and how we think.',
            'text' => 'We are codebar – a small team based in the Basel region. We bring ideas to life: from the first sketch to software in daily use. We see ourselves as the link between business and technology – we listen, think economically on your behalf, and build on open technologies.',
        ],
        'what_we_do' => [
            'title' => 'What we do',
            'command' => 'what-we-do',
            'teaser' => 'Four areas, one path.',
            'text' => 'Our work covers the entire journey of a digital solution:',
            'items' => [
                '<b>Concept design</b> – we sharpen your requirements and make ideas tangible early with clickable prototypes.',
                '<b>Software development</b> – portals, interfaces and automation, built with open technologies such as Laravel.',
                '<b>DMS/ECM</b> – as a DocuWare Silver & Cloud Partner, we take you into the paperless office and automate your processes.',
                '<b>Open-source ERP</b> – we run Odoo ourselves and, as an Odoo partner, guide you step by step through the rollout.',
            ],
        ],
        'how_we_work' => [
            'title' => 'How we work',
            'command' => 'how-we-work',
            'teaser' => 'Listen, do the maths honestly, build.',
            'text' => 'We start by listening. Because a good solution begins with understanding the problem – and honestly checking whether it pays off for you. We then shape a concept together, guided by your processes and by the people who will work with it every day. If the implementation is within our expertise, we stay with you into daily operations. If it isn\'t, we say so openly – and are glad when others turn our plans into reality.',
        ],
    ],
    'explore' => [
        'title' => 'Discover more',
        'home' => 'Back to the start page.',
        'services' => 'Four areas, one path.',
        'team' => 'The people behind codebar.',
        'news' => 'Insights from our day-to-day work.',
        'ai' => 'AI on our own infrastructure.',
        'network' => 'Our partners and communities.',
        'contact' => 'Let\'s talk.',
    ],
    'services' => [
        'header' => 'Four areas, one path: from the first idea through concept and build to day-to-day operations we support long term.',
    ],
    'docuware' => [
        'label' => 'DocuWare',
        'dms_ecm' => [
            'crumb' => 'DMS/ECM',
            'title' => 'DMS/ECM with DocuWare',
            'lead' => 'As a DocuWare Silver & Cloud Partner we roll out document management, turn processes into workflows and support day-to-day operations.',
            'export_title' => 'Getting documents back out',
            'export_teaser' => 'Holdings have to leave the system too: during a migration, for retention beyond the life of the system, or as a second copy on storage of your own.',
            'to_export' => 'To the DocuWare export',
        ],
        'export' => [
            'crumb' => 'DocuWare export',
            'title' => 'Exporting documents out of DocuWare',
            'lead' => 'We export your holdings out of DocuWare — once, or repeatedly on a schedule — in their original format and with every index field, into a folder structure you define.',
            'cases' => [
                'title' => 'When an export is needed',
                'items' => [
                    '<b>System change.</b> A DMS is being replaced. The holdings have to come out completely and verifiably before the instance is switched off.',
                    '<b>Retention.</b> Statutory retention periods run ten years and more — often beyond the life of the system itself.',
                    '<b>Second copy.</b> A copy outside the DMS, on storage where you control retention and versioning yourself.',
                    '<b>Handing over part of the archive.</b> A tenant, a site or a business unit is separated out and passed on.',
                ],
            ],
            'modes' => [
                'title' => 'Options',
                'once' => [
                    'title' => 'One-time export',
                    'body' => 'A defined set moves in a single run.',
                    'for' => 'Migration · Archive handover · Decommissioning',
                ],
                'scheduled' => [
                    'title' => 'Scheduled export',
                    'body' => 'The export runs on a fixed schedule and transfers whatever has arrived since the last run.',
                    'for' => 'Ongoing backup · Retention · Second copy',
                ],
            ],
            'scope' => [
                'title' => 'What is transferred',
                'items' => [
                    ['term' => 'Source', 'value' => 'DocuWare Cloud and on-premise.'],
                    ['term' => 'Format', 'value' => 'Unchanged, in the original format, with or without annotations and stamps.'],
                    ['term' => 'Metadata', 'value' => 'Every index field comes along.'],
                    ['term' => 'Structure', 'value' => 'Folder path and filename freely defined from your index fields.'],
                    ['term' => 'Destination', 'value' => 'Amazon S3, S3-compatible storage such as MinIO, DigitalOcean Spaces and Backblaze B2, Azure Blob Storage and SFTP.'],
                ],
            ],
            'access' => [
                'title' => 'Access and data handling',
                'items' => [
                    '<b>Credentials</b> are valid for the run and are not kept afterwards.',
                    '<b>Transfer</b> goes straight into your storage, with nothing parked with us.',
                    '<b>A record</b> of every single document, so you can hold the run against the source.',
                ],
            ],
            'process' => [
                'title' => 'How we go about it',
                'items' => [
                    ['title' => 'Survey the holdings', 'body' => 'Which file cabinets, how many documents, which index fields carry the structure — and what of it should actually come along.'],
                    ['title' => 'Settle the structure', 'body' => 'Which fields folder paths and filenames are built from, and how empty fields, duplicates and special characters are handled.'],
                    ['title' => 'Test run', 'body' => 'A limited slice goes through, so you check structure and naming against a real result rather than a concept.'],
                    ['title' => 'The run itself', 'body' => 'We start it and watch it. For recurring exports we set up the schedule and tell you when a run does not come through cleanly.'],
                    ['title' => 'Sign-off', 'body' => 'Report, spot checks, handover to you or to whatever system takes it from here.'],
                ],
            ],
            'timing' => [
                'title' => 'What that means in time',
                'body' => 'The DocuWare interface caps throughput at roughly one document per second. 100,000 documents therefore take about a day, a million correspondingly longer. We schedule large runs so they do not slow down day-to-day work.',
            ],
            'cta' => [
                'title' => 'Let us look at your holdings',
                'body' => 'Tell us which file cabinet needs to come out and roughly how large it is. We will estimate effort and duration, and tell you what we need to know first.',
                'back' => 'Back to DMS/ECM',
            ],
        ],
    ],
    'team' => [
        'header' => 'Small by conviction: you work directly with the people who understand your solution – and who build it themselves.',
        'working_title' => 'How we work',
        'working_body' => 'A small team with no layer in between: the people who build your project are the ones in the room. That saves a translation step and makes commitments binding.',
        'learning_body' => 'Training and knowledge transfer are part of how we work. We pass knowledge around the team rather than leaving it with individuals – which keeps projects independent of any single person and keeps us learning.',
    ],
    'contact' => [
        'header' => 'Got an idea, a project, or simply a question? Tell us about it – we\'ll listen and get back to you right away.',
    ],
    'contact_cta' => [
        'title' => 'Interested?',
        'teaser' => 'Let\'s talk.',
    ],
    'ai' => [
        'title' => 'AI at codebar',
        'intro' => 'We\'re at the beginning of our AI journey – and open about what already runs: local open-source models on our own hardware.',
        'llm_teaser' => 'For a few months now, we\'ve been working our way into the topic, use case by use case. Here we keep track of which models we run – and how intensively.',
        'to_models' => 'View the models',
        'to_analytics' => 'View usage analytics',
        'local_title' => 'Why local?',
        'local_body' => 'Customer data does not leave our infrastructure. That is the main reason we run open-source models ourselves instead of sending requests to a cloud provider. The trade-off: a little less performance at the top end – in exchange for full control over where data sits and what it costs.',
        'usage_body' => 'We use AI where it measurably takes work off our hands: reading documents, sorting receipts, writing and reviewing code. Where it does not, we leave it alone. The usage figures show, unfiltered, how often that actually happens.',
        'stats' => [
            'tokens_month' => 'Tokens this month',
            'requests_month' => 'Requests this month',
            'input' => 'Input',
            'output' => 'Output',
        ],
    ],
    'ai_llm_analytics' => [
        'title' => 'LLM usage analytics',
        'intro' => 'Token consumption and requests of our locally run models, broken down per month and model – updated continuously.',
        'filter' => [
            'year_label' => 'Year',
            'all_years' => 'All years',
            'month_label' => 'Month',
            'all_months' => 'All months',
            'model_label' => 'Model',
            'all_models' => 'All models',
            'other_models' => 'Other',
            'apply' => 'Show',
        ],
        'table' => [
            'title' => 'Details',
            'period' => 'Period',
            'prompt_tokens' => 'Prompt tokens',
            'completion_tokens' => 'Completion tokens',
            'total_tokens' => 'Total tokens',
            'requests' => 'Requests',
            'total' => 'Total',
        ],
        'empty' => 'No usage data available yet.',
        'last_synced' => 'Last updated on :datetime.',
        'back' => 'Back to our LLMs',
    ],
    'ai_llm' => [
        'title' => 'Our local LLMs in action',
        'intro' => 'These are the local open-source models we currently rely on – all running on our own infrastructure, in our office basement.',
        'categories' => [
            'reasoning_coding' => [
                'title' => 'Reasoning & coding',
                'description' => 'The thinkers: complex analysis, business logic and help with programming.',
            ],
            'vision_documents' => [
                'title' => 'Vision & documents',
                'description' => 'The eyes: understanding images and turning scans into usable text.',
            ],
            'retrieval_search' => [
                'title' => 'Retrieval & search',
                'description' => 'The memory: finds the right content in large data sets.',
            ],
        ],
        'tooltips' => [
            'provider' => 'Who develops this model',
            'ram' => 'Memory the model needs on our server',
            'link' => 'Where we get the model from',
        ],
        'licenses' => [
            'mit' => [
                'label' => 'MIT',
                'tooltip' => 'MIT license: very permissive open source — free to use, including commercially',
            ],
            'apache' => [
                'label' => 'Apache 2.0',
                'tooltip' => 'Apache 2.0: open source with patent grant — free to use, including commercially',
            ],
            'gemma' => [
                'label' => 'Gemma license',
                'tooltip' => 'Google\'s own license with usage terms',
            ],
        ],
        'infrastructure' => [
            'title' => 'Our infrastructure',
            'intro' => 'This is where our local models run.',
            'items' => [
                'hardware' => [
                    'label' => 'Hardware',
                    'text' => 'MacBook Pro 16" M5 Max, 128 GB RAM.',
                ],
                'management' => [
                    'label' => 'Model management',
                    'text' => 'LiteLLM for management and authorization, Ollama runs the models.',
                ],
                'access' => [
                    'label' => 'Access & security',
                    'text' => 'Cloudflare Tunnel to the local MacBook.',
                ],
                'power' => [
                    'label' => 'Power',
                    'text' => 'UPS Ubiquiti UniFi.',
                ],
            ],
        ],
        'stats' => [
            'title' => 'Usage',
            'intro' => 'How intensively our models are currently in use.',
        ],
        'archive' => [
            'title' => 'Archive',
            'intro' => 'Models we\'ve replaced – for the record.',
            'columns' => [
                'model' => 'Old model',
                'replaced_by' => 'Replaced by',
            ],
        ],
    ],

    'language_suggestion' => [
        'message' => 'This page is also available in English.',
        'action' => 'Switch to English',
        'dismiss' => 'Dismiss this notice',
    ],
];
