<?php

return [
    'intro' => [
        'title' => 'Bringing innovative ideas to life',
        'who_we_are' => [
            'title' => 'Who we are',
            'text' => 'We are codebar – a small team based in the Basel region. We bring ideas to life: from the first sketch to software in daily use. We see ourselves as the link between business and technology – we listen, think economically on your behalf, and build on open technologies.',
        ],
        'what_we_do' => [
            'title' => 'What we do',
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
            'text' => 'We start by listening. Because a good solution begins with understanding the problem – and honestly checking whether it pays off for you. We then shape a concept together, guided by your processes and by the people who will work with it every day. If the implementation is within our expertise, we stay with you into daily operations. If it isn\'t, we say so openly – and are glad when others turn our plans into reality.',
        ],
    ],
    'explore' => [
        'title' => 'Discover more',
        'home' => 'Back to the start page.',
        'services' => 'Four areas, one path.',
        'team' => 'The people behind codebar.',
        'ai' => 'AI on our own infrastructure.',
        'network' => 'Our partners and communities.',
        'contact' => 'Let\'s talk.',
    ],
    'services' => [
        'header' => 'Four areas, one path: from the first idea to day-to-day operations.',
    ],
    'team' => [
        'header' => 'Small by conviction: you work directly with the people who understand your solution – and build it.',
    ],
    'contact' => [
        'header' => 'Got an idea or a project? Tell us about it – we\'ll listen.',
    ],
    'contact_cta' => [
        'title' => 'Interested?',
        'teaser' => 'Let\'s talk.',
    ],
    'ai' => [
        'title' => 'AI at codebar',
        'intro' => 'We\'re at the beginning of our AI journey – and open about what\'s already running: local open-source models on our own infrastructure.',
        'llm_teaser' => 'For a few months now, we\'ve been working our way into the topic, use case by use case. Here we keep track of which models we run – and how intensively.',
        'to_models' => 'View the models',
        'to_analytics' => 'View usage analytics',
        'stats' => [
            'tokens_month' => 'Tokens this month',
            'requests_month' => 'Requests this month',
            'input' => 'Input',
            'output' => 'Output',
        ],
    ],
    'ai_llm_analytics' => [
        'title' => 'LLM usage analytics',
        'intro' => 'Token consumption and requests of our locally run models – per month and model.',
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
        'last_synced' => 'Last updated on :datetime, data up to and including :date.',
        'back' => 'Back to our LLMs',
    ],
    'ai_llm' => [
        'title' => 'Our local LLMs in action',
        'intro' => 'These are the local open-source models we currently rely on. We cover as much of our AI work as possible with local models – all running on our own infrastructure, in the basement of our own office.',
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
];
