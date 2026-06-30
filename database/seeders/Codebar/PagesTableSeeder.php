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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'description' => 'Stay up to date with the latest news, expert insights and trends on software development, open technologies and digital innovation from codebar.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'description' => 'Get to know codebar solutions AG – your Swiss partner for conceptual software development using open technologies and standards.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'description' => 'Wir hören zu, denken konzeptionell und entwickeln Software, die sich an den Bedürfnissen der Nutzer:innen orientiert. Mit offenen Technologien und Standards.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'description' => 'Lerne die codebar solutions AG kennen – dein Schweizer Partner für konzeptionelle Softwareentwicklung mit offenen Technologien und Standards.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'description' => 'Wir beginnen mit dem Zuhören, dann erarbeiten wir gemeinsam Konzepte basierend auf den Anforderungen künftiger Nutzer:innen. Vom Konzept bis zur Umsetzung.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'description' => 'Unsere Softwarelösungen orientieren sich an den Bedürfnissen der Nutzer:innen und bieten echten Mehrwert durch offene Technologien und Standards.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'description' => 'Hast du eine innovative Idee? Wir beginnen mit dem Zuhören, um deine Bedürfnisse zu verstehen, dann arbeiten wir gemeinsam daran, deine Vision zum Leben zu erwecken.',
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
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
                'image' => 'https://res.cloudinary.com/codebar/image/upload/c_scale,dpr_2.0,f_auto,q_auto,w_1200/www-codebar-ch/seo/seo_codebar.webp',
            ]
        );
    }
}
