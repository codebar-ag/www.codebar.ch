<?php

return [
    'intro' => [
        'who_we_are' => [
            'title' => 'Wer wir sind',
            'text' => 'Wir sind codebar – ein Team, das innovative Ideen mit digitalen Hilfsmitteln zum Leben erweckt. Wir denken wirtschaftlich, arbeiten konzeptionell und setzen konsequent auf offene Technologien und Standards. So entstehen digitale Lösungen, die sich an den Bedürfnissen ihrer Nutzer:innen orientieren – ob wir sie beraten, konzipieren, entwickeln oder einführen. Und die dir echten Mehrwert bieten.',
        ],
        'what_we_do' => [
            'title' => 'Was wir machen',
            'text' => 'Unsere Expertise deckt den ganzen Weg einer digitalen Lösung ab. Am Anfang steht die Konzeption: Wir erfassen und schärfen Anforderungen, bis daraus ein durchdachtes Konzept entsteht – und machen Ideen mit klickbaren Prototypen früh erlebbar. Darauf baut die individuelle Softwareentwicklung auf: Portallösungen, Schnittstellen und Integrationen, mit starkem Fokus auf Open Source. Derselbe offene Ansatz prägt unseren neusten Kompetenzbereich, die Open-Source-ERP-Systeme: Wir begleiten Unternehmen bei Evaluation, Einführung und Anpassung – flexibel, transparent und ohne Lizenz-Lock-in. Und im Dokumentenmanagement bringen wir langjährige Erfahrung mit: von der Beratung bis zur umgesetzten DMS- und ECM-Lösung.',
        ],
        'how_we_work' => [
            'title' => 'Wie wir arbeiten',
            'text' => 'Am Anfang hören wir dir zu. Denn ob neue Software, ERP-Einführung oder Dokumentenmanagement: Zuerst muss man im Detail verstehen, worum es geht und was die Lösung leisten soll. Anschliessend erarbeiten wir gemeinsam ein Konzept, das sich an deinen Prozessen und den Anforderungen der künftigen Nutzer:innen orientiert. Liegt der Lösungsansatz in unserer Expertise, begleiten wir dich gerne bis zur Umsetzung – und darüber hinaus. Andernfalls freuen wir uns, wenn andere unsere Pläne in die Tat umsetzen.',
        ],
    ],
    'explore' => [
        'title' => 'Mehr entdecken',
        'home' => 'Zurück zur Startseite.',
        'services' => 'Konzeption, Software, ERP und DMS/ECM.',
        'team' => 'Die Menschen hinter codebar.',
        'ai' => 'KI auf eigener Infrastruktur.',
        'network' => 'Unsere Partner und Communities.',
        'contact' => 'Lass uns sprechen.',
    ],
    'contact_cta' => [
        'title' => 'Interessiert?',
        'teaser' => 'Lassen Sie uns sprechen.',
    ],
    'ai' => [
        'title' => 'KI bei codebar',
        'intro' => 'Wie wir künstliche Intelligenz in unserer eigenen Arbeit einsetzen — von den lokalen Modellen, die wir betreiben, bis zur Infrastruktur dahinter.',
        'llm_teaser' => 'Die lokalen Open-Source-Sprachmodelle, auf die wir setzen, und die Infrastruktur, die sie betreibt.',
        'to_models' => 'Zu den Modellen',
        'to_analytics' => 'Zur Nutzungsstatistik',
        'stats' => [
            'tokens_month' => 'Tokens diesen Monat',
            'requests_month' => 'Anfragen diesen Monat',
            'input' => 'Input',
            'output' => 'Output',
        ],
    ],
    'ai_llm_analytics' => [
        'title' => 'LLM-Nutzungsstatistik',
        'intro' => 'Token-Verbrauch und Anfrage-Statistiken unserer lokal betriebenen LLMs — aggregiert pro Monat und Modell.',
        'filter' => [
            'year_label' => 'Jahr',
            'all_years' => 'Alle Jahre',
            'month_label' => 'Monat',
            'all_months' => 'Alle Monate',
            'model_label' => 'Modell',
            'all_models' => 'Alle Modelle',
            'other_models' => 'Andere',
            'apply' => 'Anzeigen',
        ],
        'table' => [
            'title' => 'Details',
            'period' => 'Zeitraum',
            'prompt_tokens' => 'Input-Tokens',
            'completion_tokens' => 'Output-Tokens',
            'total_tokens' => 'Tokens total',
            'requests' => 'Anfragen',
            'total' => 'Total',
        ],
        'empty' => 'Noch keine Nutzungsdaten vorhanden.',
        'back' => 'Zurück zu unseren LLMs',
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
            'intro' => 'Hier laufen unsere lokalen Modelle.',
            'items' => [
                'hardware' => [
                    'label' => 'Hardware',
                    'text' => 'MacBook Pro 16" M5 Max, 128 GB RAM.',
                ],
                'management' => [
                    'label' => 'Modellverwaltung',
                    'text' => 'LiteLLM für Verwaltung und Autorisierung, Ollama betreibt die Modelle.',
                ],
                'access' => [
                    'label' => 'Zugang & Sicherheit',
                    'text' => 'Cloudflare Tunnel auf das lokale MacBook.',
                ],
                'power' => [
                    'label' => 'Strom',
                    'text' => 'USV Ubiquiti UniFi.',
                ],
            ],
        ],
        'stats' => [
            'title' => 'Nutzung',
            'intro' => 'So intensiv sind unsere lokalen Modelle aktuell im Einsatz.',
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
