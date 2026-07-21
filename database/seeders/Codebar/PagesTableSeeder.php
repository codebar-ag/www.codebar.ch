<?php

namespace Database\Seeders\Codebar;

use App\Enums\LocaleEnum;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->deCH();
        $this->enCH();
    }

    private function enCH()
    {
        $locale = LocaleEnum::EN->value;

        Page::updateOrCreate(
            [
                'key' => 'start.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Bringing Innovative Ideas to Life',
                'description' => 'We listen, think conceptually, and develop software around user needs using open technologies. Your ideas, our expertise.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'news.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'News & Insights',
                'description' => 'Latest news and expert insights on software development, open technologies and digital innovation from codebar.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'about-us.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'About Us – codebar solutions AG',
                'description' => 'Meet codebar solutions AG – your Swiss partner for conceptual software development with open technologies.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'services.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Conceptual Software Development',
                'description' => 'We start by listening, then work with you to develop concepts based on future user needs. From concept to implementation.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'products.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'User-Centric Software Solutions',
                'description' => 'Our software solutions are built around the needs of users, delivering real value through open technologies and standards.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'legal.imprint.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Legal Notice',
                'description' => 'All legal details about codebar solutions AG.',
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
                'key' => 'contact.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Let\'s Talk',
                'description' => 'Have an innovative idea? We start by listening to understand your needs, then work with you to bring your vision to life.',
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

    private function deCH()
    {
        $locale = LocaleEnum::DE->value;

        Page::updateOrCreate(
            [
                'key' => 'start.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Innovative Ideen zum Leben erwecken',
                'description' => 'Wir hören zu, denken konzeptionell und entwickeln nutzerzentrierte Software mit offenen Technologien und Standards.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'news.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Neuigkeiten & Insights',
                'description' => 'Aktuelle News, Fachbeiträge und Trends rund um Softwareentwicklung, offene Technologien und digitale Innovation von codebar.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'about-us.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Über uns – codebar solutions AG',
                'description' => 'Lerne codebar solutions AG kennen – dein Schweizer Partner für konzeptionelle Softwareentwicklung mit offenen Technologien.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'services.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Konzeptionelle Softwareentwicklung',
                'description' => 'Wir hören zu, erarbeiten Konzepte für künftige Nutzer:innen und setzen sie um – von der Idee bis zur Software.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'products.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Nutzerzentrierte Softwarelösungen',
                'description' => 'Nutzerzentrierte Softwarelösungen mit echtem Mehrwert – entwickelt mit offenen Technologien und Standards.',
            ]
        );

        Page::updateOrCreate(
            [
                'key' => 'legal.imprint.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Rechtliches',
                'description' => 'Alle rechtlichen Informationen zur codebar solutions AG.',
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
                'key' => 'contact.index',
                'locale' => $locale,
            ],
            [
                'robots' => 'index,follow',
                'title' => 'Lass uns sprechen',
                'description' => 'Hast du eine innovative Idee? Wir hören zu, verstehen deine Bedürfnisse und erwecken deine Vision zum Leben.',
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
    }
}
