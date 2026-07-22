<?php

return [
    'intro' => [
        'who_we_are' => [
            'title' => 'Who we are',
            'text' => 'We are codebar – a team that brings innovative ideas to life using digital tools. We think economically, work conceptually and consistently rely on open technologies and standards. The result: digital solutions built around the needs of their users – whether we advise on them, design them, develop them or roll them out. And that deliver real value to you.',
        ],
        'what_we_do' => [
            'title' => 'What we do',
            'text' => 'Our expertise covers the entire journey of a digital solution. It starts with concept design: we capture and sharpen requirements until they form a well-thought-out concept – and make ideas tangible early on with clickable prototypes. Individual software development builds on that: portal solutions, interfaces and integrations, with a strong focus on open source. The same open approach shapes our newest area of expertise, open-source ERP systems: we support companies through evaluation, rollout and customisation – flexible, transparent and free of licence lock-in. And in document management, we bring years of experience: from consulting all the way to a fully implemented DMS or ECM solution.',
        ],
        'how_we_work' => [
            'title' => 'How we work',
            'text' => 'We start by listening. Because whether it\'s new software, an ERP rollout or document management: you first need to understand in detail what it\'s about and what the solution needs to deliver. Next, we work with you to develop a concept based on your processes and the needs of future users. If the approach falls within our expertise, we\'re happy to support you all the way to implementation – and beyond. If not, we\'re just as pleased when others turn our plans into reality.',
        ],
    ],
    'explore' => [
        'title' => 'Discover more',
        'home' => 'Back to the start page.',
        'services' => 'Concept, software, ERP and DMS/ECM.',
        'team' => 'The people behind codebar.',
        'ai' => 'AI on our own infrastructure.',
        'network' => 'Our partners and communities.',
        'contact' => 'Let\'s talk.',
    ],
    'contact_cta' => [
        'title' => 'Interested?',
        'teaser' => 'Let\'s talk.',
    ],
    'ai' => [
        'title' => 'AI at codebar',
        'intro' => 'How we use artificial intelligence in our own work — from the local models we run to the infrastructure behind them.',
        'llm_teaser' => 'The local open-source language models we rely on and the infrastructure that runs them.',
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
        'intro' => 'Token usage and request statistics of our locally hosted LLMs — aggregated per month and per model.',
        'filter' => [
            'year_label' => 'Year',
            'all_years' => 'All years',
            'month_label' => 'Month',
            'all_months' => 'All months',
            'model_label' => 'Model',
            'all_models' => 'All models',
            'other_models' => 'Other',
            'apply' => 'Apply',
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
        'back' => 'Back to our LLMs',
    ],
    'ai_llm' => [
        'title' => 'Our local LLMs in action',
        'intro' => 'These are the local open-source models we currently rely on. We aim to cover as much of our AI work as possible with local models — all of them run on our own infrastructure, in our very own office basement.',
        'categories' => [
            'reasoning_coding' => [
                'title' => 'Reasoning & coding',
                'description' => 'The thinkers: complex analysis, business logic and help with programming.',
            ],
            'vision_documents' => [
                'title' => 'Vision & documents',
                'description' => 'The eyes: understanding images and turning scanned documents into usable text.',
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
            'intro' => 'How intensively our local models are currently in use.',
        ],
        'archive' => [
            'title' => 'Archive',
            'intro' => 'Models we have since replaced — kept here for the record.',
            'columns' => [
                'model' => 'Old model',
                'replaced_by' => 'Replaced by',
            ],
        ],
    ],
];
