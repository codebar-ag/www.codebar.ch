<?php

return [
    'docuware' => [
        'showme' => [
            'title' => 'Erleben Sie DocuWare in Aktion',
            'teaser' => 'Entdecken Sie interaktive Touren rund um intelligente Dokumentenverarbeitung, Rechnungsfreigabe und
                Vertragsmanagement. Sehen Sie live, wie DocuWare Prozesse vereinfacht, Fehler reduziert und Ihr Business
                effizienter macht.',
            'buttons' => [
                'discover_now' => 'Jetzt entdecken',
                'more' => 'Mehr über DocuWare',
            ],
        ],
    ],
    'what_we_do' => [
        'title' => 'Was wir tun',
        'items' => [
            'concept' => [
                'title' => 'Konzeption & Prototyping',
                'description' => 'Von der ersten Idee über das Konzept zum klickbaren Prototyp.',
            ],
            'development' => [
                'title' => 'Individuelle Softwareentwicklung',
                'description' => 'Portallösungen, Schnittstellen und Integrationen – mit Open Source im Fokus.',
            ],
            'dms' => [
                'title' => 'DMS/ECM Consulting & Implementation',
                'description' => 'Beratung und Umsetzung rund um digitales Dokumentenmanagement.',
            ],
        ],
    ],
    'contact_cta' => [
        'title' => 'Interessiert?',
        'teaser' => 'Lassen Sie uns sprechen.',
    ],
    'ai' => [
        'title' => 'KI bei codebar',
        'intro' => 'Wie wir künstliche Intelligenz in unserer eigenen Arbeit einsetzen — von den lokalen Modellen, die wir betreiben, bis zur Infrastruktur dahinter.',
        'llm_teaser' => 'Die lokalen Open-Source-Sprachmodelle, auf die wir setzen, und die Infrastruktur, die sie betreibt.',
        'more_info' => 'Mehr erfahren',
    ],
    'ai_llm' => [
        'title' => 'Unsere lokalen LLMs im Einsatz',
        'intro' => 'Auf diese lokalen Open-Source-Modelle setzen wir aktuell. Wir versuchen, so viel wie möglich unserer KI-Arbeit mit lokalen Modellen abzubilden — alle laufen auf unserer eigenen Infrastruktur, im hauseigenen Bürokeller.',
        'categories' => [
            'reasoning_coding' => [
                'title' => 'Reasoning & Coding',
                'description' => 'Die Denker: komplexe Analysen, Geschäftslogik und Unterstützung beim Programmieren.',
            ],
            'vision_documents' => [
                'title' => 'Vision & Dokumente',
                'description' => 'Die Augen: Bilder verstehen und gescannte Dokumente in verwertbaren Text verwandeln.',
            ],
            'retrieval_search' => [
                'title' => 'Retrieval & Suche',
                'description' => 'Das Gedächtnis: findet in grossen Datenbeständen die passenden Inhalte.',
            ],
        ],
        'tooltips' => [
            'provider' => 'Wer dieses Modell entwickelt',
            'ram' => 'Arbeitsspeicher, den das Modell auf unserem Server benötigt',
            'link' => 'Woher wir das Modell beziehen',
        ],
        'licenses' => [
            'mit' => [
                'label' => 'MIT',
                'tooltip' => 'MIT-Lizenz: sehr freizügige Open-Source-Lizenz — freie Nutzung, auch kommerziell',
            ],
            'apache' => [
                'label' => 'Apache 2.0',
                'tooltip' => 'Apache 2.0: Open Source mit Patentschutz — freie Nutzung, auch kommerziell',
            ],
            'gemma' => [
                'label' => 'Gemma-Lizenz',
                'tooltip' => 'Googles eigene Lizenz mit Nutzungsbedingungen',
            ],
        ],
        'infrastructure' => [
            'title' => 'Unsere Infrastruktur',
            'intro' => 'Unsere Modelle laufen vollständig auf eigener, lokaler Infrastruktur — bei uns im Büro, nicht in der Cloud. Alle Daten bleiben im Haus.',
            'items' => [
                'hardware' => [
                    'label' => 'Hardware',
                    'text' => 'MacBook Pro 16" M5 Max, 128 GB RAM.',
                ],
                'management' => [
                    'label' => 'Modellverwaltung',
                    'text' => 'LiteLLM Studio für Verwaltung und Autorisierung, Ollama betreibt die Modelle.',
                ],
                'access' => [
                    'label' => 'Zugang & Sicherheit',
                    'text' => 'Cloudinary-Tunnel auf das lokale MacBook.',
                ],
                'power' => [
                    'label' => 'Strom',
                    'text' => 'USV Ubiquiti UniFi.',
                ],
            ],
        ],
        'archive' => [
            'title' => 'Archiv',
            'intro' => 'Modelle, die wir inzwischen abgelöst haben — der Vollständigkeit halber.',
            'columns' => [
                'model' => 'Altes Modell',
                'replaced_by' => 'Abgelöst durch',
            ],
        ],
    ],
];
