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
                'description' => 'Join codebar solutions AG – open positions and how we work as a team building software with open technologies.',
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
                'description' => 'The general terms and conditions of codebar solutions AG.',
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
                'description' => 'Download official codebar logos and brand assets for press and partner use.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'AI at codebar',
                'description' => 'How we use artificial intelligence in our own work — from the local models we run to the infrastructure behind them.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'network.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Our Network – Partners, Sponsoring & Certifications',
                'description' => 'The companies and communities we work with: collaboration, software and infrastructure partners, sponsoring like BaselHack, and Swiss quality certifications.',
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
                'title' => 'Our local LLMs in action',
                'description' => 'These are the local open-source models we currently rely on — all of them run on our own infrastructure, in our very own office basement.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.llm.analytics.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'LLM usage analytics',
                'description' => 'Token usage and request statistics of our locally hosted LLMs — aggregated per month and per model.',
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
                'description' => 'Werde Teil der codebar solutions AG – offene Stellen und wie wir als Team Software mit offenen Technologien entwickeln.',
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
                'description' => 'Die allgemeinen Geschäftsbedingungen der codebar solutions AG.',
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
                'description' => 'Offizielle codebar-Logos und Markenassets für Presse und Partner.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'KI bei codebar',
                'description' => 'Wie wir künstliche Intelligenz in unserer eigenen Arbeit einsetzen — von den lokalen Modellen, die wir betreiben, bis zur Infrastruktur dahinter.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.llm.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Unsere lokalen LLMs im Einsatz',
                'description' => 'Auf diese lokalen Open-Source-Modelle setzen wir aktuell — sie laufen alle auf unserer eigenen Infrastruktur, im hauseigenen Bürokeller.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'ai.llm.analytics.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'LLM-Nutzungsstatistik',
                'description' => 'Token-Verbrauch und Anfrage-Statistiken unserer lokal betriebenen LLMs — aggregiert pro Monat und Modell.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'network.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Unser Netzwerk – Partner, Sponsoring & Zertifizierungen',
                'description' => 'Die Firmen und Communities, mit denen wir arbeiten: Collaboration-, Software- und Infrastruktur-Partner, Sponsoring wie der BaselHack und Schweizer Qualitätszertifizierungen.',
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
