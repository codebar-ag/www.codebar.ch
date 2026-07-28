<?php

namespace Database\Seeders;

use App\Enums\LocaleEnum;
use App\Models\Page;
use Database\Seeders\Concerns\ReadsCsv;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    use ReadsCsv;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->live();
        $this->upcoming();
    }

    /**
     * Pages already published in production — sourced from the current data export.
     */
    private function live(): void
    {
        collect($this->readCsv('pages.csv'))
            ->groupBy('key')
            ->each(function ($rows, string $key) {
                $byLocale = $rows->keyBy('locale');
                $first = $rows->first() ?? [];

                Page::updateOrCreate(
                    ['key' => $key],
                    [
                        'robots' => $first['robots'] ?? '',
                        'title' => $byLocale->map(fn (array $row) => $row['title'])->all(),
                        'description' => $byLocale->map(fn (array $row) => $row['description'])->all(),
                        'created_at' => $first['created_at'] ?? now(),
                        'updated_at' => $first['updated_at'] ?? now(),
                    ]
                );
            });
    }

    /**
     * Pages for sections not yet shipped to production — no export to seed from yet.
     */
    private function upcoming(): void
    {
        $pages = [
            'technologies.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'Offene Technologien & Standards – codebar Solutions AG', 'description' => 'Die offenen Technologien und Standards, auf denen unsere Software basiert – bewährt, transparent und herstellerunabhängig.'],
                'en_CH' => ['title' => 'Open Technologies & Standards – codebar Solutions AG', 'description' => 'The open technologies and standards we build our software on – proven, transparent and vendor-independent.'],
            ],
            'co-working.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'Co-Working bei codebar Solutions AG', 'description' => 'Ein professioneller Arbeitsplatz in Oberwil — ruhig, gut ausgestattet und Teil eines Tech-Teams.'],
                'en_CH' => ['title' => 'Co-Working at codebar Solutions AG', 'description' => 'A professional workspace in Oberwil — quiet, well-equipped, and embedded in a tech team.'],
            ],
            'open-source.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'Open-Source-Beiträge – codebar Solutions AG', 'description' => 'Unsere Beiträge an die Open-Source-Community – Packages, Tools und Libraries, entwickelt und gepflegt von codebar.'],
                'en_CH' => ['title' => 'Open Source Contributions – codebar Solutions AG', 'description' => 'Our contributions to the open source community – packages, tools and libraries developed and maintained by codebar.'],
            ],
            'jobs.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'Stellen – arbeiten bei codebar Solutions AG', 'description' => 'Klein aus Überzeugung: Verantwortung ab Tag eins, offene Technologien und Praktika mitten im Projektalltag. So arbeitest du bei codebar.'],
                'en_CH' => ['title' => 'Jobs – Working at codebar Solutions AG', 'description' => 'Small by conviction: responsibility from day one, open technologies and internships inside real projects. This is what working at codebar is like.'],
            ],
            'legal.terms.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'AGB – codebar Solutions AG', 'description' => 'Die Allgemeinen Geschäftsbedingungen der codebar Solutions AG: Angebote, Projektabwicklung, Nutzungsrechte, Haftung und Support.'],
                'en_CH' => ['title' => 'Terms & Conditions – codebar Solutions AG', 'description' => 'The general terms and conditions of codebar Solutions AG: offers, project delivery, rights of use, liability and support.'],
            ],
            'legal.privacy.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'Datenschutz – codebar Solutions AG', 'description' => 'Wie wir Personendaten auf codebar.ch bearbeiten: welche Daten anfallen, wofür wir sie nutzen, wie lange wir sie aufbewahren – und welche Rechte du hast.'],
                'en_CH' => ['title' => 'Privacy – codebar Solutions AG', 'description' => 'How we process personal data on codebar.ch: what we collect, why, how long we keep it – and the rights you have.'],
            ],
            'media.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'Medien & Markenassets – codebar Solutions AG', 'description' => 'Offizielle codebar-Logos für Presse und Partner – farbig, invertiert, schwarz-weiss, als PNG und SVG.'],
                'en_CH' => ['title' => 'Media & Brand Assets – codebar Solutions AG', 'description' => 'Official codebar logos for press and partners – colour, inverted, black and white, as PNG and SVG.'],
            ],
            'ai.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'KI bei codebar Solutions AG – lokale Modelle, eigene Infrastruktur', 'description' => 'Transparenz statt Buzzwords: Wir zeigen offen, wie wir KI in der eigenen Arbeit einsetzen – mit lokalen Open-Source-Modellen und echten Nutzungszahlen.'],
                'en_CH' => ['title' => 'AI at codebar Solutions AG – Local Models, Own Infrastructure', 'description' => 'Transparency over buzzwords: an open look at how we use AI in our own work – with local open-source models and real usage figures.'],
            ],
            'ai.llm.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'Unsere lokalen LLMs im Einsatz – codebar Solutions AG', 'description' => 'Diese lokalen Open-Source-Modelle betreiben wir aktuell – auf eigener Hardware im hauseigenen Bürokeller. Modelle, Infrastruktur und Nutzung.'],
                'en_CH' => ['title' => 'Our Local LLMs in Action – codebar Solutions AG', 'description' => 'The local open-source models we currently run – on our own hardware, in our own office basement. Models, infrastructure and usage.'],
            ],
            'ai.llm.analytics.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'LLM-Nutzungsstatistik – codebar Solutions AG', 'description' => 'Token-Verbrauch und Anfragen unserer lokal betriebenen Modelle – transparent aufgeschlüsselt pro Monat und Modell.'],
                'en_CH' => ['title' => 'LLM Usage Analytics – codebar Solutions AG', 'description' => 'Token consumption and requests of our locally run models – transparently broken down per month and model.'],
            ],
            'network.index' => [
                'robots' => 'index,follow',
                'de_CH' => ['title' => 'Netzwerk – Partner & Community | codebar Solutions AG', 'description' => 'Mit wem wir arbeiten: Projekt-, Software- und Infrastrukturpartner, unser Engagement in der Community – und die Labels, die dahinterstehen.'],
                'en_CH' => ['title' => 'Network – Partners & Community | codebar Solutions AG', 'description' => 'Who we work with: project, software and infrastructure partners, our community engagement – and the labels behind it.'],
            ],
            'network.request.index' => [
                'robots' => 'noindex,nofollow',
                'de_CH' => ['title' => 'Netzwerk-Profil aktualisieren – codebar Solutions AG', 'description' => 'Persönlichen Link anfordern, um das eigene Profil im codebar Netzwerk zu aktualisieren.'],
                'en_CH' => ['title' => 'Update network profile – codebar Solutions AG', 'description' => 'Request a personal link to update your codebar network profile.'],
            ],
        ];

        foreach ($pages as $key => $data) {
            Page::updateOrCreate(
                ['key' => $key],
                [
                    'robots' => $data['robots'],
                    'title' => [
                        LocaleEnum::DE->value => $data['de_CH']['title'],
                        LocaleEnum::EN->value => $data['en_CH']['title'],
                    ],
                    'description' => [
                        LocaleEnum::DE->value => $data['de_CH']['description'],
                        LocaleEnum::EN->value => $data['en_CH']['description'],
                    ],
                ]
            );
        }
    }
}
