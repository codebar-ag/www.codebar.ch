<?php

declare(strict_types=1);

return [
    'intro' => [
        'title' => 'Schön, bist du da',
        'legend' => 'Was möchtest du über uns wissen?',
        'shortcuts' => 'Abschnitt wechseln: Zifferntasten 1 bis 3 oder die Pfeiltasten links und rechts.',
        'prev_section' => 'Vorheriger Abschnitt',
        'next_section' => 'Nächster Abschnitt',
        'next' => 'weiter: :title',
        'expertise' => 'schau dir unsere Expertise an',
        'cta' => 'erzähl uns von deinem Projekt',
        'who_we_are' => [
            'title' => 'Wer wir sind',
            'command' => 'wer-wir-sind',
            'teaser' => 'Wer wir sind und wie wir denken.',
            'text' => 'Wir sind codebar – ein kleines Team aus der Region Basel. Wir erwecken Ideen zum Leben: von der ersten Skizze bis zur Software im täglichen Einsatz. Dabei verstehen wir uns als Bindeglied zwischen Business und Technik – wir hören zu, denken wirtschaftlich mit und bauen auf offene Technologien.',
        ],
        'what_we_do' => [
            'title' => 'Was wir machen',
            'command' => 'was-wir-machen',
            'teaser' => 'Vier Bereiche, ein Weg.',
            'text' => 'Unsere Arbeit deckt den ganzen Weg einer digitalen Lösung ab:',
            'items' => [
                '<b>Konzeption</b> – wir schärfen deine Anforderungen und machen Ideen mit klickbaren Prototypen früh greifbar.',
                '<b>Softwareentwicklung</b> – Portale, Schnittstellen und Automatisierungen, entwickelt mit offenen Technologien wie Laravel.',
                '<b>DMS/ECM</b> – als DocuWare Silver & Cloud Partner begleiten wir dich ins papierlose Büro und automatisieren deine Prozesse.',
                '<b>Open Source ERP</b> – Odoo setzen wir selbst ein und begleiten dich als Odoo-Partner Schritt für Schritt bei der Einführung.',
            ],
        ],
        'how_we_work' => [
            'title' => 'Wie wir arbeiten',
            'command' => 'wie-wir-arbeiten',
            'teaser' => 'Zuhören, ehrlich rechnen, umsetzen.',
            'text' => 'Am Anfang hören wir dir zu. Denn eine gute Lösung beginnt damit, das Problem zu verstehen – und ehrlich zu prüfen, ob sie sich für dich rechnet. Danach entwickeln wir gemeinsam ein Konzept, das sich nach deinen Prozessen richtet und nach den Menschen, die täglich damit arbeiten. Liegt die Umsetzung in unserer Expertise, begleiten wir dich bis in den Betrieb. Wenn nicht, sagen wir es offen – und freuen uns, wenn andere unsere Pläne verwirklichen.',
        ],
    ],
    'explore' => [
        'title' => 'Mehr entdecken',
        'home' => 'Zurück zur Startseite.',
        'services' => 'Vier Bereiche, ein Weg.',
        'team' => 'Die Menschen hinter codebar.',
        'news' => 'Einblicke aus unserem Alltag.',
        'ai' => 'KI auf eigener Infrastruktur.',
        'network' => 'Unsere Partner und Communities.',
        'contact' => 'Lass uns sprechen.',
    ],
    'services' => [
        'header' => 'Vier Bereiche, ein Weg: von der ersten Idee über Konzept und Umsetzung bis zum Betrieb, den wir langfristig begleiten.',
    ],
    'docuware' => [
        'label' => 'DocuWare',
        'dms_ecm' => [
            'crumb' => 'DMS/ECM',
            'title' => 'DMS/ECM mit DocuWare',
            'lead' => 'Als DocuWare Silver & Cloud Partner führen wir Dokumentenmanagement ein, bilden Prozesse ab und begleiten den Betrieb.',
            'export_title' => 'Dokumente wieder herausholen',
            'export_teaser' => 'Ein Bestand muss auch wieder aus dem System heraus: beim Systemwechsel, für die Aufbewahrung über die Systemlaufzeit hinaus oder als Zweitkopie auf eigenem Speicher.',
            'to_export' => 'Zum DocuWare-Export',
        ],
        'export' => [
            'crumb' => 'DocuWare Export',
            'title' => 'Dokumente aus DocuWare exportieren',
            'lead' => 'Wir exportieren deinen Bestand aus DocuWare — einmalig oder wiederkehrend nach Zeitplan — im Originalformat und mit allen Indexfeldern, in eine Ordnerstruktur, die du festlegst.',
            'cases' => [
                'title' => 'Wann ein Export gebraucht wird',
                'items' => [
                    '<b>Systemwechsel.</b> Ein DMS wird abgelöst. Der Bestand muss vollständig und nachvollziehbar heraus, bevor die Instanz abgeschaltet wird.',
                    '<b>Aufbewahrung.</b> Gesetzliche Aufbewahrungsfristen laufen zehn Jahre und länger — oft über die Lebensdauer des Systems hinaus.',
                    '<b>Zweitkopie.</b> Eine Kopie ausserhalb des DMS, auf Speicher, dessen Aufbewahrung und Versionierung du selbst steuerst.',
                    '<b>Teilbestand übergeben.</b> Ein Mandant, ein Standort oder ein Geschäftsbereich wird herausgelöst und weitergegeben.',
                ],
            ],
            'modes' => [
                'title' => 'Möglichkeiten',
                'once' => [
                    'title' => 'Einmaliger Export',
                    'body' => 'Ein definierter Bestand geht in einem Lauf über.',
                    'for' => 'Systemwechsel · Archivübergabe · Stilllegung',
                ],
                'scheduled' => [
                    'title' => 'Wiederkehrender Export',
                    'body' => 'Der Export läuft nach festem Zeitplan und überträgt, was seit dem letzten Lauf dazugekommen ist.',
                    'for' => 'Laufende Sicherung · Aufbewahrung · Zweitkopie',
                ],
            ],
            'scope' => [
                'title' => 'Was übertragen wird',
                'items' => [
                    ['term' => 'Quelle', 'value' => 'DocuWare Cloud und On-Premise.'],
                    ['term' => 'Format', 'value' => 'Unverändert im Originalformat, wahlweise mit oder ohne Anmerkungen und Stempel.'],
                    ['term' => 'Metadaten', 'value' => 'Alle Indexfelder werden mitgeführt.'],
                    ['term' => 'Struktur', 'value' => 'Ordnerpfad und Dateiname frei definierbar aus deinen Indexfeldern.'],
                    ['term' => 'Ziel', 'value' => 'Amazon S3, S3-kompatibler Speicher wie MinIO, DigitalOcean Spaces und Backblaze B2, Azure Blob Storage und SFTP.'],
                ],
            ],
            'access' => [
                'title' => 'Zugriff und Datenhaltung',
                'items' => [
                    '<b>Zugangsdaten</b> gelten nur für den Lauf und werden danach nicht aufbewahrt.',
                    '<b>Übertragung</b> direkt in deinen Speicher, ohne Zwischenablage bei uns.',
                    '<b>Nachweis</b> über jedes einzelne Dokument, damit du den Lauf gegen den Bestand halten kannst.',
                ],
            ],
            'process' => [
                'title' => 'Wie wir vorgehen',
                'items' => [
                    ['title' => 'Bestand sichten', 'body' => 'Welche File Cabinets, wie viele Dokumente, welche Indexfelder tragen die Struktur — und was davon überhaupt mit soll.'],
                    ['title' => 'Struktur festlegen', 'body' => 'Aus welchen Feldern Ordnerpfade und Dateinamen entstehen, und wie mit leeren Feldern, Dubletten und Sonderzeichen umgegangen wird.'],
                    ['title' => 'Testlauf', 'body' => 'Ein begrenzter Ausschnitt geht durch, damit du Struktur und Benennung am echten Ergebnis prüfst statt an einem Konzept.'],
                    ['title' => 'Durchführung', 'body' => 'Der Lauf wird gestartet und überwacht. Bei wiederkehrenden Exporten richten wir den Zeitplan ein und melden uns, wenn ein Lauf nicht sauber durchläuft.'],
                    ['title' => 'Abnahme', 'body' => 'Bericht, Stichproben, Übergabe an dich oder an das System, das weitermacht.'],
                ],
            ],
            'timing' => [
                'title' => 'Was das zeitlich heisst',
                'body' => "Die DocuWare-Schnittstelle begrenzt den Durchsatz auf rund ein Dokument pro Sekunde. Ein Bestand von 100'000 Dokumenten braucht damit etwa einen Tag, eine Million entsprechend länger. Grosse Läufe planen wir so, dass sie den laufenden Betrieb nicht ausbremsen.",
            ],
            'cta' => [
                'title' => 'Bestand besprechen',
                'body' => 'Sag uns, welches File Cabinet heraus soll und wie gross der Bestand ungefähr ist. Wir schätzen Aufwand und Dauer ab und sagen dir, was wir vorher wissen müssen.',
                'back' => 'Zurück zu DMS/ECM',
            ],
        ],
    ],
    'team' => [
        'header' => 'Klein aus Überzeugung: Bei uns arbeitest du direkt mit den Menschen, die deine Lösung verstehen – und bauen.',
        'working_title' => 'Wie wir arbeiten',
        'working_body' => 'Ein kleines Team, keine Zwischenebene: Wer dein Projekt baut, sitzt auch im Gespräch. Das spart einen Übersetzungsschritt und macht Zusagen verbindlich.',
        'learning_body' => 'Ausbildung und Wissenstransfer gehören für uns dazu. Wir geben Wissen im Team weiter, statt es auf einzelnen Köpfen liegen zu lassen – das hält Projekte unabhängig von einzelnen Personen und uns als Team lernfähig.',
    ],
    'contact' => [
        'header' => 'Du hast eine Idee, ein Projekt oder einfach eine Frage? Erzähl uns davon – wir hören zu und melden uns umgehend zurück.',
    ],
    'contact_cta' => [
        'title' => 'Interessiert?',
        'teaser' => 'Lassen Sie uns sprechen.',
    ],
    'ai' => [
        'title' => 'KI bei codebar',
        'intro' => 'Wir stehen bei KI am Anfang – und zeigen offen, was bereits läuft: lokale Open-Source-Modelle auf eigener Infrastruktur.',
        'llm_teaser' => 'Seit ein paar Monaten arbeiten wir uns in das Thema ein, Use Case für Use Case. Hier dokumentieren wir laufend, welche Modelle wir einsetzen – und wie intensiv.',
        'to_models' => 'Zu den Modellen',
        'to_analytics' => 'Zur Nutzungsstatistik',
        'local_title' => 'Warum lokal?',
        'local_body' => 'Kundendaten verlassen unsere Infrastruktur nicht. Das ist der Hauptgrund, warum wir Open-Source-Modelle selbst betreiben, statt Anfragen an einen Cloud-Anbieter zu schicken. Was wir dafür in Kauf nehmen: etwas weniger Leistung an der Spitze – dafür volle Kontrolle darüber, wo Daten liegen und was sie kosten.',
        'usage_body' => 'Wir setzen KI dort ein, wo sie uns messbar Arbeit abnimmt: beim Lesen von Dokumenten, beim Einordnen von Belegen, beim Schreiben und Prüfen von Code. Wo sie das nicht tut, lassen wir es. Die Nutzungsstatistik zeigt ungefiltert, wie oft das tatsächlich vorkommt.',
        'stats' => [
            'tokens_month' => 'Tokens diesen Monat',
            'requests_month' => 'Anfragen diesen Monat',
            'input' => 'Input',
            'output' => 'Output',
        ],
    ],
    'ai_llm_analytics' => [
        'title' => 'LLM-Nutzungsstatistik',
        'intro' => 'Token-Verbrauch und Anfragen unserer lokal betriebenen Modelle – pro Monat und Modell, laufend aktualisiert.',
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
        'last_synced' => 'Zuletzt aktualisiert am :datetime.',
        'back' => 'Zurück zu unseren LLMs',
    ],
    'ai_llm' => [
        'title' => 'Unsere lokalen LLMs im Einsatz',
        'intro' => 'Auf diese lokalen Open-Source-Modelle setzen wir aktuell – alle laufen auf eigener Infrastruktur im hauseigenen Bürokeller.',
        'categories' => [
            'reasoning_coding' => [
                'title' => 'Reasoning & Coding',
                'description' => 'Die Denker: komplexe Analysen, Geschäftslogik und Unterstützung beim Programmieren.',
            ],
            'vision_documents' => [
                'title' => 'Vision & Dokumente',
                'description' => 'Die Augen: Bilder verstehen und Scans in verwertbaren Text verwandeln.',
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
            'intro' => 'So intensiv sind unsere Modelle aktuell im Einsatz.',
        ],
        'archive' => [
            'title' => 'Archiv',
            'intro' => 'Modelle, die wir abgelöst haben – der Vollständigkeit halber.',
            'columns' => [
                'model' => 'Altes Modell',
                'replaced_by' => 'Abgelöst durch',
            ],
        ],
    ],

    'language_suggestion' => [
        'message' => 'Diese Seite gibt es auch auf Deutsch.',
        'action' => 'Auf Deutsch wechseln',
        'dismiss' => 'Hinweis schliessen',
    ],
];
