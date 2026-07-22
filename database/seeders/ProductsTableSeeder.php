<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->seed(
            order: 1,
            sharedSlug: 'flows',
            localizedData: [
                LocaleEnum::DE->value => [
                    'name' => 'Flows',
                    'headline' => 'Dokumentenprozesse, gesteuert von Agenten. Auf Infrastruktur, die du kontrollierst.',
                    'teaser' => 'Flows ist eine Orchestrierungsplattform für dokumentenbasierte Prozesse — verbinde dein DMS und lass KI-Agenten Dokumente automatisch extrahieren, validieren und verarbeiten.',
                    'tags' => [],
                    'content' => <<<'MD'
                        ## Extraktionstools lesen Dokumente. Sie verstehen dein Geschäft nicht.

                        Zwei Probleme halten Dokumentenprozesse manuell.

                        Das erste ist der Aufwand: Dein DMS speichert Dokumente zuverlässig, aber die Arbeit drumherum – Lesen, Indexieren, Validieren, Übertragen von Daten in andere Systeme – hängt weiterhin von Personen ab, die Dateien öffnen und Daten eintippen. Das skaliert nur durch zusätzliches Personal.

                        Das zweite Problem liegt tiefer: Klassische Dokumentenextraktion liefert Felder, nicht mehr. Sie versteht den Inhalt nicht und kann das Ergebnis nicht gegen deine eigenen Daten prüfen – existiert dieser Lieferant, stimmt der Betrag mit der Bestellung überein, ist das die richtige Kostenstelle? Selbst mit einem Extraktionstool muss also weiterhin eine Person jedes Ergebnis prüfen, bevor es vertrauenswürdig ist.
                        MD,
                    'features_heading' => 'Orchestrierte Agenten-Workflows – extrahieren, verstehen, validieren, zurückschreiben.',
                    'features_intro' => 'Flows verbindet sich mit deinem bestehenden DMS. Keine Migration; dein Archiv bleibt, wo es ist. Darauf konfigurierst du Workflows, die auslösen, sobald ein Dokument eintrifft.',
                    'features' => [
                        ['title' => 'Agentische Extraktion mit Validierung', 'description' => 'Definiere das Schema, das du brauchst. Agenten extrahieren die Daten, validieren sie gegen deine eigenen Systeme und Daten und schreiben geprüfte Ergebnisse zurück – strukturiert und passend zu deinen Feldnamen.'],
                        ['title' => 'Multi-Agenten-Workflow-Orchestrierung', 'description' => 'Für komplexe Prozesse verkettest du spezialisierte Agenten – Klassifizierung, Validierung, Anreicherung, Routing. Die Orchestrierung basiert auf dem Microsoft Agent Framework, und MCP-Tool-Integrationen verbinden deine Workflows direkt mit deinen Geschäftssystemen und Daten.'],
                        ['title' => 'Prompt- und Agenten-Engineering', 'description' => 'Verbessere Prompts anhand echter Produktionsdaten, generiere sie aus dem Wissen in deinem Datenpool oder DMS und verwalte Prompts und Schemas in einer versionierten, wiederverwendbaren Knowledge-Bibliothek.'],
                        ['title' => 'Modell- und Anbieterfreiheit', 'description' => 'Führe Workflows bei dem Anbieter aus, der zu deinen Anforderungen an Genauigkeit, Kosten und Datenresidenz passt – und vergleiche denselben Workflow über mehrere Modelle hinweg, um zu sehen, welches bei deinen Dokumenten am besten abschneidet. Ein Wechsel bedeutet keinen Neuaufbau. Eigene lokale Modelle (Bring-your-own) folgen als Nächstes.'],
                        ['title' => 'Vollständige Nachvollziehbarkeit', 'description' => 'Jeder Lauf wird lückenlos protokolliert: Dokument, Modell, Tokens, Dauer, Ergebnis.'],
                    ],
                    'deployment_heading' => 'Drei Wege, Flows zu betreiben. Alle isoliert.',
                    'deployment_intro' => 'Jede Bereitstellung erhält ihre eigene Datenbank, Schlüsselverwaltung, Speicher und Endpunkte – automatisch bereitgestellt, mit Firewalling und automatischer Rotation der Secrets. Du entscheidest, wo sie läuft, und wählst bei jeder Option die Azure-Region deiner Bereitstellung, sofern diese die benötigten Dienste unterstützt.',
                    'deployment_options' => [
                        ['title' => 'Auf deiner eigenen Azure-Infrastruktur', 'description' => 'Flows wird in deine bestehende Azure-Umgebung integriert. Dein Abonnement, deine Governance, deine Kontrolle.'],
                        ['title' => 'Dediziertes Azure-Abonnement', 'description' => 'Ein vollständig isoliertes Abonnement, das für dich betrieben wird – maximale Trennung, ohne dass du es selbst betreiben musst.'],
                        ['title' => 'Gemeinsames Abonnement, dedizierte Ressourcengruppe', 'description' => 'Deine eigene Ressourcengruppe und Bereitstellungen innerhalb eines gemeinsamen Abonnements – isolierte Ressourcen bei geringerem Fussabdruck.'],
                    ],
                    'cta_heading' => 'Interessiert?',
                    'cta_body' => 'Wir zeigen dir Flows anhand deiner eigenen Dokumente.',
                ],
                LocaleEnum::EN->value => [
                    'name' => 'Flows',
                    'headline' => 'Document workflows, run by agents. On infrastructure you control.',
                    'teaser' => 'Flows is an orchestration platform for document-based processes — connect your DMS and let AI agents extract, validate, and process documents automatically.',
                    'tags' => [],
                    'content' => <<<'MD'
                        ## Extraction tools read documents. They don't understand your business.

                        Two problems keep document processes manual.

                        The first is effort: your DMS stores documents reliably, but the work around it — reading, indexing, validating, moving data into other systems — still depends on people opening files and typing. It only scales by hiring.

                        The second is deeper: conventional document extraction returns fields, nothing more. It doesn't understand the content, and it can't check the result against your own data — does this supplier exist, does the amount match the order, is this the right cost center? So even with an extraction tool, a person still has to verify every result before it can be trusted.
                        MD,
                    'features_heading' => 'Orchestrated agent workflows — extract, understand, validate, write back.',
                    'features_intro' => 'Flows connects to your existing DMS. No migration; your archive stays where it is. On top of it, you configure workflows that trigger the moment a document arrives.',
                    'features' => [
                        ['title' => 'Agentic extraction with validation', 'description' => 'Define the schema you need. Agents extract the data, validate it against your own systems and data, and write verified results back — structured and keyed to your field names.'],
                        ['title' => 'Multi-agent workflow orchestration', 'description' => 'For complex processes, chain specialized agents — classification, validation, enrichment, routing. Orchestration is built on the Microsoft Agent Framework, and MCP tool integrations connect your workflows directly to your business systems and data.'],
                        ['title' => 'Prompt & agent engineering', 'description' => 'Improve prompts from real production data, generate them from the knowledge in your data pool or DMS, and manage prompts and schemas in a versioned, reusable Knowledge library.'],
                        ['title' => 'Model and provider freedom', 'description' => "Run workflows on the provider that fits your accuracy, cost, and data-residency requirements — and benchmark the same workflow against multiple models to see which performs best on your documents. Switching doesn't mean rebuilding. Bring-your-own local models is next."],
                        ['title' => 'Full traceability', 'description' => 'Every run is logged end to end: document, model, tokens, duration, result.'],
                    ],
                    'deployment_heading' => 'Three ways to run Flows. All isolated.',
                    'deployment_intro' => 'Every deployment gets its own database, key management, storage, and endpoints — provisioned automatically, with firewalling and automatic secret rotation. You choose where it runs, and with each option, you decide the Azure region of your deployment, provided it supports the services the platform requires.',
                    'deployment_options' => [
                        ['title' => 'On your own Azure infrastructure', 'description' => 'Flows is deployed into your existing Azure environment. Your subscription, your governance, your controls.'],
                        ['title' => 'Dedicated Azure subscription', 'description' => 'A fully isolated subscription operated for you — maximum separation without running it yourself.'],
                        ['title' => 'Shared subscription, dedicated resource group', 'description' => 'Your own resource group and deployments within a shared subscription — isolated resources at a lighter footprint.'],
                    ],
                    'cta_heading' => 'Interested?',
                    'cta_body' => "We'll walk you through Flows on your own documents.",
                ],
            ]
        );
    }

    private function seed(int $order, string $sharedSlug, array $localizedData): void
    {
        $entries = collect($localizedData)->map(function ($data, $locale) use ($sharedSlug, $order) {
            $slug = Str::slug($sharedSlug, '-', $locale);

            return Product::updateOrCreate(
                [
                    'locale' => $locale,
                    'slug' => $slug,
                ],
                [
                    'published' => true,
                    'order' => $order,
                    'name' => Arr::get($data, 'name'),
                    'headline' => Arr::get($data, 'headline'),
                    'teaser' => Arr::get($data, 'teaser'),
                    'tags' => Arr::get($data, 'tags', []),
                    'image' => Arr::get($data, 'image', 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-paperflakes-ch/seo/seo_paperflakes.webp'),
                    'content' => Arr::get($data, 'content'),
                    'features_heading' => Arr::get($data, 'features_heading'),
                    'features_intro' => Arr::get($data, 'features_intro'),
                    'features' => Arr::get($data, 'features', []),
                    'deployment_heading' => Arr::get($data, 'deployment_heading'),
                    'deployment_intro' => Arr::get($data, 'deployment_intro'),
                    'deployment_options' => Arr::get($data, 'deployment_options', []),
                    'cta_heading' => Arr::get($data, 'cta_heading'),
                    'cta_body' => Arr::get($data, 'cta_body'),
                ]
            );
        });

        $entries->each(function (Product $entry) use ($entries) {
            $entries->each(function (Product $reference) use ($entry) {
                $entry->references()->updateOrCreate([
                    'reference_type' => get_class($reference),
                    'reference_id' => $reference->id,
                    'reference_locale' => $reference->locale,
                ]);
            });
        });
    }
}
