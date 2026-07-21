<?php

namespace App\Http\Controllers\Demo;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FlowsLayoutDemoController extends Controller
{
    /**
     * @return array<string, array{title: string, description: string}>
     */
    public static function variants(): array
    {
        return [
            'saas-landing' => [
                'title' => 'Klassische SaaS-Landingpage',
                'description' => 'Grosser Gradient-Hero, Feature-Grid, sticky CTA-Leiste — vertraute Marketing-Rhythmik.',
            ],
            'editorial' => [
                'title' => 'Editorial / Long-Form',
                'description' => 'Magazin-artige einspaltige Leseerfahrung mit Initiale, Zwischentiteln und viel Weissraum.',
            ],
            'bento' => [
                'title' => 'Bento-Grid',
                'description' => 'Asymmetrisches Kachelraster im Stil moderner Produktseiten (Apple/Linear).',
            ],
            'terminal' => [
                'title' => 'Terminal / Dev-Konsole',
                'description' => 'Dunkles, monospace-geprägtes Layout mit Terminal-Fenster-Chrome — technische Zielgruppe.',
            ],
            'big-statement' => [
                'title' => 'Grosse Aussagen / Scroll-Story',
                'description' => 'Überdimensionierte Typografie, eine Kernaussage pro Abschnitt, filmisches Scrollen.',
            ],
            'docs-split' => [
                'title' => 'Split Sticky Docs',
                'description' => 'Linke fixierte Mini-Navigation, rechts scrollender Inhalt — wie moderne Doku-Seiten.',
            ],
            'journey' => [
                'title' => 'Nummerierte Reise',
                'description' => 'Alles als durchnummerierte Schritte mit verbindender Zeitlinie erzählt.',
            ],
            'before-after' => [
                'title' => 'Vorher / Nachher',
                'description' => 'Kontrastreiche Zweispalten-Gegenüberstellung als Erzählprinzip.',
            ],
            'swiss-grid' => [
                'title' => 'Swiss Minimalist Grid',
                'description' => 'Strenges Raster, feine Linien, Kapitälchen-Labels — reduziert und hochwertig.',
            ],
            'tabs-dashboard' => [
                'title' => 'Interaktives Tab-Dashboard',
                'description' => 'Abschnitte als Tabs/Accordion erkundbar — produkttour-artiges Gefühl.',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function content(): array
    {
        return [
            'headline' => 'Dokumentenprozesse, gesteuert von Agenten. Auf Infrastruktur, die du kontrollierst.',
            'subheadline' => 'Flows ist eine Orchestrierungsplattform für dokumentenbasierte Prozesse — verbinde dein DMS und lass KI-Agenten Dokumente automatisch extrahieren, validieren und verarbeiten.',
            'problem' => [
                'heading' => 'Extraktionstools lesen Dokumente. Sie verstehen dein Geschäft nicht.',
                'intro' => 'Zwei Probleme halten Dokumentenprozesse manuell.',
                'paragraphs' => [
                    'Das erste ist der Aufwand: Dein DMS speichert Dokumente zuverlässig, aber die Arbeit drumherum – Lesen, Indexieren, Validieren, Übertragen von Daten in andere Systeme – hängt weiterhin von Personen ab, die Dateien öffnen und Daten eintippen. Das skaliert nur durch zusätzliches Personal.',
                    'Das zweite Problem liegt tiefer: Klassische Dokumentenextraktion liefert Felder, nicht mehr. Sie versteht den Inhalt nicht und kann das Ergebnis nicht gegen deine eigenen Daten prüfen – existiert dieser Lieferant, stimmt der Betrag mit der Bestellung überein, ist das die richtige Kostenstelle? Selbst mit einem Extraktionstool muss also weiterhin eine Person jedes Ergebnis prüfen, bevor es vertrauenswürdig ist.',
                ],
            ],
            'features' => [
                'heading' => 'Orchestrierte Agenten-Workflows – extrahieren, verstehen, validieren, zurückschreiben.',
                'intro' => 'Flows verbindet sich mit deinem bestehenden DMS. Keine Migration; dein Archiv bleibt, wo es ist. Darauf konfigurierst du Workflows, die auslösen, sobald ein Dokument eintrifft.',
                'items' => [
                    ['title' => 'Agentische Extraktion mit Validierung', 'description' => 'Definiere das Schema, das du brauchst. Agenten extrahieren die Daten, validieren sie gegen deine eigenen Systeme und Daten und schreiben geprüfte Ergebnisse zurück – strukturiert und passend zu deinen Feldnamen.'],
                    ['title' => 'Multi-Agenten-Workflow-Orchestrierung', 'description' => 'Für komplexe Prozesse verkettest du spezialisierte Agenten – Klassifizierung, Validierung, Anreicherung, Routing. Die Orchestrierung basiert auf dem Microsoft Agent Framework, und MCP-Tool-Integrationen verbinden deine Workflows direkt mit deinen Geschäftssystemen und Daten.'],
                    ['title' => 'Prompt- und Agenten-Engineering', 'description' => 'Verbessere Prompts anhand echter Produktionsdaten, generiere sie aus dem Wissen in deinem Datenpool oder DMS und verwalte Prompts und Schemas in einer versionierten, wiederverwendbaren Knowledge-Bibliothek.'],
                    ['title' => 'Modell- und Anbieterfreiheit', 'description' => 'Führe Workflows bei dem Anbieter aus, der zu deinen Anforderungen an Genauigkeit, Kosten und Datenresidenz passt – und vergleiche denselben Workflow über mehrere Modelle hinweg, um zu sehen, welches bei deinen Dokumenten am besten abschneidet. Ein Wechsel bedeutet keinen Neuaufbau. Eigene lokale Modelle (Bring-your-own) folgen als Nächstes.'],
                    ['title' => 'Vollständige Nachvollziehbarkeit', 'description' => 'Jeder Lauf wird lückenlos protokolliert: Dokument, Modell, Tokens, Dauer, Ergebnis.'],
                ],
            ],
            'deployment' => [
                'heading' => 'Drei Wege, Flows zu betreiben. Alle isoliert.',
                'intro' => 'Jede Bereitstellung erhält ihre eigene Datenbank, Schlüsselverwaltung, Speicher und Endpunkte – automatisch bereitgestellt, mit Firewalling und automatischer Rotation der Secrets. Du entscheidest, wo sie läuft, und wählst bei jeder Option die Azure-Region deiner Bereitstellung, sofern diese die benötigten Dienste unterstützt.',
                'options' => [
                    ['title' => 'Auf deiner eigenen Azure-Infrastruktur', 'description' => 'Flows wird in deine bestehende Azure-Umgebung integriert. Dein Abonnement, deine Governance, deine Kontrolle.'],
                    ['title' => 'Dediziertes Azure-Abonnement', 'description' => 'Ein vollständig isoliertes Abonnement, das für dich betrieben wird – maximale Trennung, ohne dass du es selbst betreiben musst.'],
                    ['title' => 'Gemeinsames Abonnement, dedizierte Ressourcengruppe', 'description' => 'Deine eigene Ressourcengruppe und Bereitstellungen innerhalb eines gemeinsamen Abonnements – isolierte Ressourcen bei geringerem Fussabdruck.'],
                ],
            ],
            'cta' => [
                'heading' => 'Interessiert?',
                'body' => 'Wir zeigen dir Flows anhand deiner eigenen Dokumente.',
                'buttonLabel' => 'Kontaktiere uns',
            ],
        ];
    }

    public function index(): View
    {
        return view('demo.flows.index', [
            'variants' => self::variants(),
        ]);
    }

    public function show(string $variant): View
    {
        if (! array_key_exists($variant, self::variants())) {
            throw new NotFoundHttpException;
        }

        return view("demo.flows.variants.{$variant}", [
            'content' => self::content(),
            'variantTitle' => self::variants()[$variant]['title'],
        ]);
    }

    /**
     * @return array<string, array{title: string, description: string}>
     */
    public static function v2Variants(): array
    {
        return [
            'flow-diagram' => [
                'title' => 'Fluss-Diagramm',
                'description' => 'Dokument → Agent → geprüftes Ergebnis als technisches Node-Diagramm im Hero.',
            ],
            'line-icons' => [
                'title' => 'Line-Icon-Set',
                'description' => 'Ein eigenes, konsistentes Strich-Icon für jedes Feature und jede Deployment-Option.',
            ],
            'blueprint' => [
                'title' => 'Blueprint',
                'description' => 'Millimeterpapier-Textur, Massketten und Eckmarken — technische Zeichnung.',
            ],
            'isometric' => [
                'title' => 'Isometrisch',
                'description' => 'Isometrische Dokumentenstapel- und Server-Illustrationen für Hero & Deployment.',
            ],
            'organic-blobs' => [
                'title' => 'Organische Blobs',
                'description' => 'Weiche Verlaufsflächen in Markenfarben hinter den nummerierten Sektionen.',
            ],
            'dot-halftone' => [
                'title' => 'Halbton-Raster',
                'description' => 'Punktraster-Illustration im Hero, Plakat-artige Druck-Ästhetik.',
            ],
            'network-nodes' => [
                'title' => 'Netzwerk-Knoten',
                'description' => 'Verbundene Knoten als Sinnbild für Multi-Agenten-Orchestrierung.',
            ],
            'stamp-seal' => [
                'title' => 'Stempel/Siegel',
                'description' => 'Kreisrunde Stempelmarken mit perforiertem Rand markieren jede Sektion.',
            ],
            'data-bars' => [
                'title' => 'Daten-Balken',
                'description' => 'Abstrakte Balken-/Wellenformen als Sinnbild für Dokumentenverarbeitung.',
            ],
            'doc-stack' => [
                'title' => 'Dokumentenstapel',
                'description' => 'Stilisierte Dokumente mit Eselsohr als durchgehendes Bildmotiv.',
            ],
        ];
    }

    public function indexV2(): View
    {
        return view('demo.flows.v2.index', [
            'variants' => self::v2Variants(),
        ]);
    }

    public function showV2(string $variant): View
    {
        if (! array_key_exists($variant, self::v2Variants())) {
            throw new NotFoundHttpException;
        }

        $product = Product::where('locale', 'de_CH')->where('slug', 'flows')->first();

        $page = $product
            ? (new PageAction)->product(product: $product)
            : (new PageAction(locale: null, routeName: 'products.index'))->default();

        return view("demo.flows.v2.variants.{$variant}", [
            'content' => self::content(),
            'variantTitle' => self::v2Variants()[$variant]['title'],
            'page' => $page,
        ]);
    }
}
