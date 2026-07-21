<?php

return [
    'docuware' => [
        'showme' => [
            'title' => 'Experience DocuWare in Action',
            'teaser' => 'Discover interactive tours focused on intelligent document processing, invoice approval, and contract management. See how DocuWare streamlines processes, reduces errors, and makes your business more efficient.',
            'buttons' => [
                'discover_now' => 'Discover Now',
                'more' => 'More about DocuWare',
            ],
        ],
    ],
    'what_we_do' => [
        'title' => 'What we do',
        'items' => [
            'concept' => [
                'title' => 'Concept design & prototyping',
                'description' => 'From the first idea and concept to a clickable prototype.',
            ],
            'development' => [
                'title' => 'Individual software development',
                'description' => 'Portal solutions, interfaces and integrations – with a focus on open source.',
            ],
            'dms' => [
                'title' => 'DMS/ECM consulting & implementation',
                'description' => 'Consulting and implementation for digital document management.',
            ],
        ],
    ],
    'contact_cta' => [
        'title' => 'Interested?',
        'teaser' => 'Let\'s talk.',
    ],
    'ai' => [
        'title' => 'AI at codebar',
        'intro' => 'How we use artificial intelligence in our own work — from the local models we run to the infrastructure behind them.',
        'llm_teaser' => 'The local open-source language models we rely on and the infrastructure that runs them.',
        'more_info' => 'More information',
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
            'status' => 'We are currently evaluating this model',
            'link' => 'Where we get the model from',
        ],
        'status_label' => 'evaluating',
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
            'intro' => 'Our models run entirely on our own local infrastructure — in our office, not in the cloud. All data stays in-house.',
            'items' => [
                'hardware' => [
                    'label' => 'Hardware',
                    'text' => 'MacBook Pro 16" M5 Max, 128 GB RAM.',
                ],
                'management' => [
                    'label' => 'Model management',
                    'text' => 'LiteLLM Studio for management and authorization, Ollama runs the models.',
                ],
                'access' => [
                    'label' => 'Access & security',
                    'text' => 'Cloudinary tunnel to the local MacBook.',
                ],
                'power' => [
                    'label' => 'Power',
                    'text' => 'UPS Ubiquiti UniFi.',
                ],
            ],
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
