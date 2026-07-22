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
        foreach ($this->readCsv('pages.csv') as $row) {
            Page::updateOrCreate(
                [
                    'key' => $row['key'],
                    'locale' => $row['locale'],
                ],
                [
                    'robots' => $row['robots'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                ]
            );
        }
    }

    /**
     * Pages for sections not yet shipped to production — no export to seed from yet.
     */
    private function upcoming(): void
    {
        $this->deCH();
        $this->enCH();
    }

    private function enCH(): void
    {
        $locale = LocaleEnum::EN->value;

        Page::updateOrCreate(
            [
                'key' => 'technologies.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Open Technologies & Standards',
                'description' => 'The open technologies and standards we build our software on – proven, transparent and vendor-independent.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'co-working.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Co-Working at codebar',
                'description' => 'A professional workspace in Oberwil — quiet, well-equipped, and embedded in a tech team.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'open-source.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Open Source Contributions',
                'description' => 'Our contributions to the open source community – packages, tools and libraries developed and maintained by codebar.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'jobs.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Jobs at codebar',
                'description' => 'Join codebar Solutions AG – open positions and how we work as a team building software with open technologies.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'legal.terms.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Terms & Conditions',
                'description' => 'The general terms and conditions of codebar Solutions AG.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'legal.privacy.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Privacy Policy – codebar',
                'description' => 'How codebar Solutions AG processes personal data on this website under Swiss data protection law.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'media.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Media & Brand Assets – codebar',
                'description' => 'Official codebar logos for press and partners – colour, inverted, black and white, as PNG and SVG.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'AI at codebar – Local Models, Own Infrastructure',
                'description' => 'Transparency over buzzwords: an open look at how we use AI in our own work – with local open-source models and real usage figures.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'network.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Network – Partners & Community | codebar',
                'description' => 'Who we work with: project, software and infrastructure partners, our community engagement – and the labels behind it.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'network.request.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'noindex,nofollow',
                'title' => 'Update network profile',
                'description' => 'Request a personal link to update your codebar network profile.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.llm.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Our Local LLMs in Action – codebar',
                'description' => 'The local open-source models we currently run – on our own hardware, in our own office basement. Models, infrastructure and usage.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.llm.analytics.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'LLM Usage Analytics – codebar',
                'description' => 'Token consumption and requests of our locally run models – transparently broken down per month and model.',
            ]
        );
    }

    private function deCH(): void
    {
        $locale = LocaleEnum::DE->value;

        Page::updateOrCreate(
            [
                'key' => 'technologies.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Offene Technologien & Standards',
                'description' => 'Die offenen Technologien und Standards, auf denen unsere Software basiert – bewährt, transparent und herstellerunabhängig.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'co-working.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Co-Working bei codebar',
                'description' => 'Ein professioneller Arbeitsplatz in Oberwil — ruhig, gut ausgestattet und Teil eines Tech-Teams.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'open-source.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Open-Source-Beiträge',
                'description' => 'Unsere Beiträge an die Open-Source-Community – Packages, Tools und Libraries, entwickelt und gepflegt von codebar.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'jobs.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Stellen bei codebar',
                'description' => 'Werde Teil der codebar Solutions AG – offene Stellen und wie wir als Team Software mit offenen Technologien entwickeln.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'legal.terms.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Allgemeine Geschäftsbedingungen',
                'description' => 'Die allgemeinen Geschäftsbedingungen der codebar Solutions AG.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'legal.privacy.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Datenschutzerklärung – codebar',
                'description' => 'Wie die codebar Solutions AG Personendaten auf dieser Website gemäss Schweizer Datenschutzrecht bearbeitet.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'media.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Medien & Markenassets – codebar',
                'description' => 'Offizielle codebar-Logos für Presse und Partner – farbig, invertiert, schwarz-weiss, als PNG und SVG.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'KI bei codebar – lokale Modelle, eigene Infrastruktur',
                'description' => 'Transparenz statt Buzzwords: Wir zeigen offen, wie wir KI in der eigenen Arbeit einsetzen – mit lokalen Open-Source-Modellen und echten Nutzungszahlen.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.llm.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Unsere lokalen LLMs im Einsatz – codebar',
                'description' => 'Diese lokalen Open-Source-Modelle betreiben wir aktuell – auf eigener Hardware im hauseigenen Bürokeller. Modelle, Infrastruktur und Nutzung.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.llm.analytics.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'LLM-Nutzungsstatistik – codebar',
                'description' => 'Token-Verbrauch und Anfragen unserer lokal betriebenen Modelle – transparent aufgeschlüsselt pro Monat und Modell.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'network.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Netzwerk – Partner & Community | codebar',
                'description' => 'Mit wem wir arbeiten: Projekt-, Software- und Infrastrukturpartner, unser Engagement in der Community – und die Labels, die dahinterstehen.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'network.request.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'noindex,nofollow',
                'title' => 'Netzwerk-Profil aktualisieren',
                'description' => 'Persönlichen Link anfordern, um das eigene Profil im codebar Netzwerk zu aktualisieren.',
            ]
        );
    }
}
