<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ServicesTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->seed(
            order: 1,
            sharedSlug: 'konzeption-prototyping',
            localizedData: [
                LocaleEnum::DE->value => [
                    'name' => 'Konzeption & Prototyping',
                    'teaser' => 'Wir bringen deine Idee aufs Papier – visuell. Mit Mockups und klickbaren Prototypen entsteht früh ein gemeinsames Bild, noch bevor die erste Zeile Code geschrieben ist. Am Ende hältst du ein technisches Konzept in der Hand, inklusive Technologiewahl – als Basis für die Umsetzung, bei uns oder bei anderen.',
                    'tags' => ['Requirements Engineering', 'Prototyping', 'UX'],
                    'content' => <<<'MD'
                        ## Von der Idee zum greifbaren Konzept

                        Am Anfang steht Zuhören: Wir wollen verstehen, was dein Vorhaben antreibt, wer es nutzen wird und was es leisten muss. Daraus entwickeln wir gemeinsam ein Konzept, das die künftigen Nutzer:innen ins Zentrum stellt — und machen es als klickbaren Prototyp erlebbar, bevor eine Zeile produktiver Code entsteht.

                        ### Unser Vorgehen

                        - **Verstehen** — In Workshops und Gesprächen klären wir Ziele, Nutzer:innen und Rahmenbedingungen.
                        - **Konzipieren** — Wir erarbeiten Informationsarchitektur, Abläufe und Datenmodell, verständlich dokumentiert.
                        - **Prototyping** — Klickbare Prototypen machen Ideen früh greifbar und diskutierbar.
                        - **Validieren** — Feedback von echten Nutzer:innen fliesst direkt in die nächste Iteration ein.

                        ### Warum sich das lohnt

                        Ein Prototyp zeigt früh, ob eine Idee trägt. Entscheidungen fallen anhand von etwas Greifbarem statt auf Papier — das reduziert Risiken und spart in der Umsetzung Zeit und Kosten. Und wenn es weitergeht, bildet das Konzept die solide Grundlage für die Entwicklung.
                        MD,
                ],
                LocaleEnum::EN->value => [
                    'name' => 'Concept design & prototyping',
                    'teaser' => 'We put your idea on paper – visually. Mockups and clickable prototypes create a shared picture early on, before a single line of code is written. In the end, you hold a technical concept in your hands, including the technology stack – as a basis for implementation, by us or by others.',
                    'tags' => ['Requirements engineering', 'Prototyping', 'UX'],
                    'content' => <<<'MD'
                        ## From idea to tangible concept

                        It starts with listening: we want to understand what drives your project, who will use it and what it needs to deliver. From there, we develop a concept together that puts future users at the centre — and bring it to life as a clickable prototype before a single line of production code is written.

                        ### How we work

                        - **Understand** — In workshops and conversations, we clarify goals, users and constraints.
                        - **Design** — We work out information architecture, workflows and data model, documented in plain language.
                        - **Prototype** — Clickable prototypes make ideas tangible and easy to discuss early on.
                        - **Validate** — Feedback from real users flows straight into the next iteration.

                        ### Why it pays off

                        A prototype shows early whether an idea holds up. Decisions are made on something tangible instead of on paper — reducing risk and saving time and cost during implementation. And when the project moves ahead, the concept forms a solid foundation for development.
                        MD,
                ],
            ]
        );

        $this->seed(
            order: 2,
            sharedSlug: 'individuelle-softwareentwicklung',
            localizedData: [
                LocaleEnum::DE->value => [
                    'name' => 'Individuelle Softwareentwicklung',
                    'teaser' => 'Portale, Schnittstellen, Automatisierungen: Software, die im Hintergrund zuverlässig ihre Arbeit macht. Wir entwickeln mit offenen Technologien wie Laravel – und betreuen deine Lösung auch nach dem Go-live, über Jahre.',
                    'tags' => ['Backend & Schnittstellen', 'Laravel', 'Open Source'],
                    'content' => <<<'MD'
                        ## Software, die zu deinen Prozessen passt

                        Standardsoftware endet dort, wo dein Geschäft besonders ist. Wir entwickeln individuelle Lösungen, die sich nach deinen Prozessen richten — nicht umgekehrt.

                        ### Was wir bauen

                        - **Portallösungen** — Kunden-, Partner- und Mitarbeiterportale mit klarer Benutzerführung.
                        - **Schnittstellen & APIs** — Saubere, dokumentierte Schnittstellen, die Systeme zuverlässig verbinden.
                        - **Integrationen** — Wir verbinden bestehende Systeme wie ERP, CRM oder DMS zu durchgängigen Abläufen.

                        ### Open Source im Fokus

                        Wir setzen auf offene Technologien und Standards. Das bedeutet: keine Lizenzabhängigkeiten, eine aktive Community hinter den eingesetzten Werkzeugen und Software, die dir gehört und die du weiterentwickeln kannst — mit uns oder ohne uns.

                        ### Nachhaltig entwickelt

                        Automatisierte Tests, Code-Reviews und kontinuierliche Deployments gehören für uns zum Handwerk. So bleibt deine Lösung auch nach Jahren wartbar und erweiterbar.
                        MD,
                ],
                LocaleEnum::EN->value => [
                    'name' => 'Individual software development',
                    'teaser' => 'Portals, interfaces, automation: software that quietly does its job in the background. We build with open technologies such as Laravel – and look after your solution long after go-live.',
                    'tags' => ['Backend & interfaces', 'Laravel', 'Open source'],
                    'content' => <<<'MD'
                        ## Software that fits your processes

                        Off-the-shelf software ends where your business is unique. We build individual solutions that adapt to your processes — not the other way around.

                        ### What we build

                        - **Portal solutions** — Customer, partner and employee portals with clear, intuitive user journeys.
                        - **Interfaces & APIs** — Clean, documented interfaces that connect systems reliably.
                        - **Integrations** — We connect existing systems such as ERP, CRM or DMS into seamless end-to-end processes.

                        ### A focus on open source

                        We rely on open technologies and standards. That means no licence lock-in, an active community behind the tools we use, and software that belongs to you and that you can keep evolving — with us or without us.

                        ### Built to last

                        Automated tests, code reviews and continuous deployments are part of our craft. That keeps your solution maintainable and extensible, even years down the road.
                        MD,
                ],
            ]
        );

        $this->seed(
            order: 3,
            sharedSlug: 'dms-ecm-consulting',
            localizedData: [
                LocaleEnum::DE->value => [
                    'name' => 'DMS/ECM',
                    'teaser' => 'Vom Papier zum papierlosen Büro – und weiter zur Automatisierung: Als DocuWare Silver & Cloud Partner digitalisieren wir deine Dokumente und bilden Prozesse in Workflows ab. Vom Einzelunternehmen bis zum Konzern mit über 200 Nutzer:innen.',
                    'tags' => ['DocuWare', 'Dokumentenmanagement', 'Workflows'],
                    'content' => <<<'MD'
                        ## Dokumentenmanagement, das im Alltag funktioniert

                        Verträge, Rechnungen, Personaldossiers: Dokumente sind das Rückgrat vieler Geschäftsprozesse. Wir beraten dich herstellerneutral und begleiten dich von der Analyse bis zum laufenden Betrieb deines DMS/ECM.

                        ### Unsere Leistungen

                        - **Analyse & Beratung** — Wir nehmen deine Dokumentenprozesse auf und zeigen, wo Digitalisierung den grössten Nutzen bringt.
                        - **Konzeption** — Ablagestruktur, Indexierung, Berechtigungen und Aufbewahrung, sauber durchdacht.
                        - **Implementierung** — Einführung und Konfiguration deines DMS — unter anderem mit DocuWare, wo wir langjährige Erfahrung mitbringen.
                        - **Integration & Automatisierung** — Anbindung an ERP, CRM und Fachanwendungen sowie automatisierte Dokumentenworkflows, bis hin zu KI-gestützter Verarbeitung.

                        ### Nach dem Go-live

                        Wir lassen dich nicht mit dem System allein: Schulung, Support und die kontinuierliche Weiterentwicklung deiner Dokumentenprozesse gehören dazu.
                        MD,
                ],
                LocaleEnum::EN->value => [
                    'name' => 'DMS/ECM',
                    'teaser' => 'From paper to a paperless office – and on to automation: as a DocuWare Silver & Cloud Partner, we digitise your documents and turn processes into workflows. From one-person businesses to corporations with more than 200 users.',
                    'tags' => ['DocuWare', 'Document management', 'Workflows'],
                    'content' => <<<'MD'
                        ## Document management that works in everyday business

                        Contracts, invoices, HR files: documents are the backbone of many business processes. We advise you independently of any vendor and support you from the first analysis through to the day-to-day operation of your DMS/ECM.

                        ### What we offer

                        - **Analysis & consulting** — We map your document processes and show where digitalisation delivers the greatest value.
                        - **Concept design** — Filing structure, indexing, permissions and retention, thought through properly.
                        - **Implementation** — Rollout and configuration of your DMS — including DocuWare, where we bring years of experience.
                        - **Integration & automation** — Connections to ERP, CRM and line-of-business applications, plus automated document workflows up to AI-assisted processing.

                        ### After go-live

                        We don't leave you alone with the system: training, support and the continuous improvement of your document processes are part of the package.
                        MD,
                ],
            ]
        );
        $this->seed(
            order: 4,
            sharedSlug: 'open-source-erp',
            localizedData: [
                LocaleEnum::DE->value => [
                    'name' => 'Open Source ERP',
                    'teaser' => 'Unser jüngster Bereich – und einer, hinter dem wir stehen: Odoo setzen wir seit Kurzem selbst als ERP ein. Als Odoo-Partner bieten wir dir Schritt für Schritt an, was sich bei uns bewährt – heute die Einführung von Projektmanagement und Zeiterfassung, ohne Lizenz-Lock-in.',
                    'tags' => ['Odoo', 'ERP-Einführung', 'Open Source'],
                    'content' => <<<'MD'
                        ## Odoo – das offene ERP

                        Unser jüngster Bereich – und einer, hinter dem wir stehen: Odoo setzen wir seit Kurzem selbst als ERP ein. Als Odoo-Partner bieten wir dir Schritt für Schritt an, was sich bei uns bewährt – heute die Einführung von Projektmanagement und Zeiterfassung, ohne Lizenz-Lock-in.
                        MD,
                ],
                LocaleEnum::EN->value => [
                    'name' => 'Open-source ERP',
                    'teaser' => 'Our newest area – and one we stand behind: we recently started running Odoo as our own ERP. As an Odoo partner, we offer you step by step what proves itself in our daily work – today, the rollout of project management and time tracking, free of licence lock-in.',
                    'tags' => ['Odoo', 'ERP rollout', 'Open source'],
                    'content' => <<<'MD'
                        ## Odoo – the open ERP

                        Our newest area – and one we stand behind: we recently started running Odoo as our own ERP. As an Odoo partner, we offer you step by step what proves itself in our daily work – today, the rollout of project management and time tracking, free of licence lock-in.
                        MD,
                ],
            ]
        );
    }

    /**
     * @param  array<string, array<string, mixed>>  $localizedData
     */
    private function seed(int $order, string $sharedSlug, array $localizedData): void
    {
        $entries = collect($localizedData)->map(function (array $data, string $locale) use ($sharedSlug, $order) {
            $slug = Str::slug($sharedSlug, '-', $locale);

            return Service::updateOrCreate(
                [
                    'locale' => $locale,
                    'slug' => $slug,
                ],
                [
                    'published' => true,
                    'order' => $order,
                    'group' => 'services',
                    'name' => Arr::get($data, 'name'),
                    'teaser' => Arr::get($data, 'teaser'),
                    'tags' => Arr::get($data, 'tags', []),
                    'content' => Arr::get($data, 'content'),
                    'url' => Arr::get($data, 'url'),
                    'image' => Arr::get($data, 'image', ''),
                ]
            );
        });

        $entries->each(function (Service $entry) use ($entries) {
            $entries->each(function (Service $reference) use ($entry) {
                $entry->references()->updateOrCreate([
                    'reference_type' => get_class($reference),
                    'reference_id' => $reference->id,
                    'reference_locale' => $reference->locale,
                ]);
            });
        });
    }
}
