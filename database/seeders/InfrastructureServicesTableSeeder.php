<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\InfrastructureLayer;
use App\Models\InfrastructureService;
use Illuminate\Database\Seeder;

class InfrastructureServicesTableSeeder extends Seeder
{
    private const array SERVICES = [
        [
            'layer' => 'network',
            'key' => 'domain',
            'order' => 1,
            'name' => [
                'de_CH' => 'Domain',
                'en_CH' => 'Domain',
            ],
            'text' => [
                'de_CH' => 'Dein Name im Internet. Wir registrieren und verlängern ihn, damit er dir gehört.',
                'en_CH' => 'Your name on the internet. We register and renew it so that it stays yours.',
            ],
            'provider' => 'Hosttech',
            'vendor' => 'Hosttech',
            'vendor_country' => 'CH',
            'place' => '',
            'countries' => ['CH'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://www.hosttech.ch',
                ],
            ],
        ],
        [
            'layer' => 'network',
            'key' => 'dns',
            'order' => 2,
            'name' => [
                'de_CH' => 'DNS',
                'en_CH' => 'DNS',
            ],
            'text' => [
                'de_CH' => 'Das Telefonbuch des Internets: übersetzt deinen Domainnamen in die Adresse des Servers.',
                'en_CH' => 'The phone book of the internet: it translates your domain name into the address of the server.',
            ],
            'provider' => 'ClouDNS',
            'vendor' => 'ClouDNS',
            'vendor_country' => 'BG',
            'place' => '',
            'countries' => ['WW'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://www.cloudns.net',
                ],
            ],
        ],
        [
            'layer' => 'network',
            'key' => 'waf',
            'order' => 4,
            'name' => [
                'de_CH' => 'WAF',
                'en_CH' => 'WAF',
            ],
            'text' => [
                'de_CH' => 'Die Firewall vor deiner Anwendung. Sie filtert Angriffe und Bots heraus, bevor sie den Server erreichen. Cloudflare arbeitet in einem weltweiten Netz, nah bei der Person.',
                'en_CH' => 'The firewall in front of your application. It filters out attacks and bots before they reach the server. Cloudflare runs on a worldwide network, close to the person.',
            ],
            'provider' => 'Cloudflare',
            'vendor' => 'Cloudflare',
            'vendor_country' => 'US',
            'place' => '',
            'countries' => ['WW'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://www.cloudflare.com',
                ],
            ],
        ],
        [
            'layer' => 'network',
            'key' => 'tls',
            'order' => 3,
            'name' => [
                'de_CH' => 'Zertifikat',
                'en_CH' => 'Certificate',
            ],
            'text' => [
                'de_CH' => 'Das Schloss in der Adresszeile: Das Zertifikat verschlüsselt die Verbindung zwischen Browser und Anwendung (HTTPS). Es wird automatisch ausgestellt und erneuert.',
                'en_CH' => 'The lock in the address bar: the certificate encrypts the connection between browser and application (HTTPS). It is issued and renewed automatically.',
            ],
            'provider' => 'Google Trust Services',
            'vendor' => 'Google',
            'vendor_country' => 'US',
            'place' => '',
            'countries' => ['WW'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://pki.goog',
                ],
            ],
        ],
        [
            'layer' => 'platform',
            'key' => 'app',
            'order' => 1,
            'name' => [
                'de_CH' => 'Application',
                'en_CH' => 'Application',
            ],
            'text' => [
                'de_CH' => 'Deine Anwendung selbst. Sie nimmt jede Anfrage an, holt die Inhalte und baut die Antwort zusammen.',
                'en_CH' => 'Your application itself. It accepts every request, fetches the content and assembles the response.',
            ],
            'provider' => 'Laravel Cloud',
            'vendor' => 'Laravel',
            'vendor_country' => 'US',
            'place' => 'Frankfurt',
            'countries' => ['DE'],
            'operation' => 'managed',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://cloud.laravel.com',
                ],
            ],
        ],
        [
            'layer' => 'platform',
            'key' => 'cache',
            'order' => 2,
            'name' => [
                'de_CH' => 'Cache',
                'en_CH' => 'Cache',
            ],
            'text' => [
                'de_CH' => 'Merkt sich fertige Ergebnisse. Was schon berechnet wurde, kommt sofort zurück.',
                'en_CH' => 'Remembers finished results. Anything already computed comes back instantly.',
            ],
            'provider' => 'Laravel Cloud',
            'vendor' => 'Laravel',
            'vendor_country' => 'US',
            'place' => 'Frankfurt',
            'countries' => ['DE'],
            'operation' => 'managed',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://cloud.laravel.com',
                ],
            ],
        ],
        [
            'layer' => 'platform',
            'key' => 'db',
            'order' => 3,
            'name' => [
                'de_CH' => 'Datenbank',
                'en_CH' => 'Database',
            ],
            'text' => [
                'de_CH' => 'Das Gedächtnis: Inhalte, Einstellungen und alles, was über Formulare hereinkommt. Neon ist die Postgres-Datenbank, die auch Laravel Cloud nutzt. Wir betreiben sie in einem eigenen Konto.',
                'en_CH' => 'The memory: content, settings and everything that comes in through forms. Neon is the Postgres database that Laravel Cloud uses as well. We run it in an account of our own.',
            ],
            'provider' => 'Neon',
            'vendor' => 'Neon',
            'vendor_country' => 'US',
            'place' => 'Frankfurt',
            'countries' => ['DE'],
            'operation' => 'managed',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://neon.com',
                ],
            ],
        ],
        [
            'layer' => 'platform',
            'key' => 'workers',
            'order' => 4,
            'name' => [
                'de_CH' => 'Workers',
                'en_CH' => 'Workers',
            ],
            'text' => [
                'de_CH' => 'Erledigen Arbeit im Hintergrund, etwa E-Mails verschicken, damit die Anwendung schnell bleibt.',
                'en_CH' => 'Handle work in the background, such as sending emails, so the application stays fast.',
            ],
            'provider' => 'Laravel Cloud',
            'vendor' => 'Laravel',
            'vendor_country' => 'US',
            'place' => 'Frankfurt',
            'countries' => ['DE'],
            'operation' => 'managed',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://cloud.laravel.com',
                ],
            ],
        ],
        [
            'layer' => 'services',
            'key' => 'turnstile',
            'order' => 1,
            'name' => [
                'de_CH' => 'Captcha',
                'en_CH' => 'Captcha',
            ],
            'text' => [
                'de_CH' => 'Prüft bei Formularen unauffällig, ob ein Mensch dahintersteckt. Ohne Bilderrätsel.',
                'en_CH' => 'Quietly checks whether a human is behind a form submission. No picture puzzles.',
            ],
            'provider' => 'Cloudflare Turnstile',
            'vendor' => 'Cloudflare',
            'vendor_country' => 'US',
            'place' => '',
            'countries' => ['WW'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://www.cloudflare.com',
                ],
            ],
        ],
        [
            'layer' => 'services',
            'key' => 'cloudinary',
            'order' => 2,
            'name' => [
                'de_CH' => 'Bilder',
                'en_CH' => 'Images',
            ],
            'text' => [
                'de_CH' => 'Liefert jedes Bild in der Grösse und im Format, das zum Gerät passt.',
                'en_CH' => 'Delivers every image in the size and format that fits the device.',
            ],
            'provider' => 'Cloudinary',
            'vendor' => 'Cloudinary',
            'vendor_country' => 'US',
            'place' => '',
            'countries' => ['US'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://cloudinary.com',
                ],
            ],
        ],
        [
            'layer' => 'services',
            'key' => 'vimeo',
            'order' => 3,
            'name' => [
                'de_CH' => 'Videos',
                'en_CH' => 'Video',
            ],
            'text' => [
                'de_CH' => 'Videos streamt Vimeo. Deine Anwendung bleibt dadurch leicht und schnell.',
                'en_CH' => 'Videos are streamed by Vimeo, which keeps your application light and fast.',
            ],
            'provider' => 'Vimeo',
            'vendor' => 'Vimeo',
            'vendor_country' => 'US',
            'place' => '',
            'countries' => ['US'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://vimeo.com',
                ],
            ],
        ],
        [
            'layer' => 'services',
            'key' => 'postmark',
            'order' => 4,
            'name' => [
                'de_CH' => 'E-Mail',
                'en_CH' => 'Email',
            ],
            'text' => [
                'de_CH' => 'Stellt die E-Mails deiner Anwendung zuverlässig zu, zum Beispiel Bestätigungen nach einem Formular.',
                'en_CH' => 'Reliably delivers the emails your application sends, for example confirmations after a form.',
            ],
            'provider' => 'Postmark',
            'vendor' => 'Postmark',
            'vendor_country' => 'US',
            'place' => '',
            'countries' => ['US'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://postmarkapp.com',
                ],
                [
                    'type' => 'location',
                    'url' => 'https://postmarkapp.com/support/article/is-postmark-secure-and-redundant',
                ],
            ],
        ],
        [
            'layer' => 'services',
            'key' => 'spaces',
            'order' => 5,
            'name' => [
                'de_CH' => 'Dateien',
                'en_CH' => 'Files',
            ],
            'text' => [
                'de_CH' => 'Die Ablage für Dateien wie PDFs und Uploads, getrennt von der Anwendung.',
                'en_CH' => 'Storage for files such as PDFs and uploads, kept separate from the application.',
            ],
            'provider' => 'DigitalOcean Spaces',
            'vendor' => 'DigitalOcean',
            'vendor_country' => 'US',
            'place' => 'Frankfurt',
            'countries' => ['DE'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://www.digitalocean.com',
                ],
            ],
        ],
        [
            'layer' => 'ai',
            'key' => 'azure',
            'order' => 1,
            'name' => [
                'de_CH' => 'Cloud-KI',
                'en_CH' => 'Cloud AI',
            ],
            'text' => [
                'de_CH' => 'KI-Modelle aus der Azure-Cloud. Je nach Modell rechnen sie in Schweden, in der Schweiz oder in den USA.',
                'en_CH' => 'AI models from the Azure cloud. Depending on the model they run in Sweden, Switzerland or the USA.',
            ],
            'provider' => 'Azure Foundry',
            'vendor' => 'Microsoft',
            'vendor_country' => 'US',
            'place' => '',
            'countries' => ['SE', 'CH', 'US'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://azure.microsoft.com',
                ],
                [
                    'type' => 'location',
                    'url' => 'https://azure.microsoft.com/explore/global-infrastructure/data-residency',
                ],
            ],
        ],
        [
            'layer' => 'ai',
            'key' => 'macs',
            'order' => 2,
            'name' => [
                'de_CH' => 'Lokale KI',
                'en_CH' => 'Local AI',
            ],
            'text' => [
                'de_CH' => 'KI-Modelle auf unseren eigenen Mac Minis und Mac Studios. Die Daten verlassen unser Haus nicht.',
                'en_CH' => 'AI models on our own Mac Minis and Mac Studios. The data never leaves our premises.',
            ],
            'provider' => 'Mac Mini/Studio',
            'vendor' => 'Apple',
            'vendor_country' => 'US',
            'place' => 'Region Basel',
            'countries' => ['CH'],
            'operation' => 'self',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://www.codebar.ch',
                ],
            ],
        ],
        [
            'layer' => 'monitoring',
            'key' => 'ohdear',
            'order' => 1,
            'name' => [
                'de_CH' => 'Uptime',
                'en_CH' => 'Uptime',
            ],
            'text' => [
                'de_CH' => 'Prüft von aussen, ob deine Anwendung erreichbar ist, und alarmiert uns bei Störungen.',
                'en_CH' => 'Checks from the outside whether your application is reachable and alerts us when something is wrong.',
            ],
            'provider' => 'Oh Dear',
            'vendor' => 'Oh Dear',
            'vendor_country' => 'BE',
            'place' => '',
            'countries' => ['BE'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://ohdear.app',
                ],
                [
                    'type' => 'location',
                    'url' => 'https://ohdear.app/features/gdpr',
                ],
            ],
        ],
        [
            'layer' => 'monitoring',
            'key' => 'logs',
            'order' => 2,
            'name' => [
                'de_CH' => 'Logs',
                'en_CH' => 'Logs',
            ],
            'text' => [
                'de_CH' => 'Protokolliert, was in der Anwendung passiert: Fehler, langsame Seiten, auffällige Abfragen.',
                'en_CH' => 'Logs what happens inside the application: errors, slow pages, unusual queries.',
            ],
            'provider' => 'Laravel Nightwatch',
            'vendor' => 'Laravel',
            'vendor_country' => 'US',
            'place' => 'Frankfurt',
            'countries' => ['DE'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://nightwatch.laravel.com',
                ],
            ],
        ],
        [
            'layer' => 'monitoring',
            'key' => 'analytics',
            'order' => 3,
            'name' => [
                'de_CH' => 'Analytics',
                'en_CH' => 'Analytics',
            ],
            'text' => [
                'de_CH' => 'Unser eigenes Werkzeug. Es zeigt, welche Seiten besucht werden.',
                'en_CH' => 'Our own tool. It shows which pages are visited.',
            ],
            'provider' => 'Internal',
            'vendor' => 'codebar Solutions AG',
            'vendor_country' => 'CH',
            'place' => 'Region Basel',
            'countries' => ['CH'],
            'operation' => 'self',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://www.codebar.ch',
                ],
            ],
        ],
        [
            'layer' => 'monitoring',
            'key' => 'feedback',
            'order' => 4,
            'name' => [
                'de_CH' => 'Feedback',
                'en_CH' => 'Feedback',
            ],
            'text' => [
                'de_CH' => 'Unser eigenes Werkzeug. Fehler und Wünsche meldest du direkt in der Anwendung, an der Stelle, um die es geht.',
                'en_CH' => 'Our own tool. Report bugs and wishes right inside the application, at the exact spot they concern.',
            ],
            'provider' => 'Internal',
            'vendor' => 'codebar Solutions AG',
            'vendor_country' => 'CH',
            'place' => 'Region Basel',
            'countries' => ['CH'],
            'operation' => 'self',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://www.codebar.ch',
                ],
            ],
        ],
        [
            'layer' => 'development',
            'key' => 'github',
            'order' => 1,
            'name' => [
                'de_CH' => 'Code',
                'en_CH' => 'Code',
            ],
            'text' => [
                'de_CH' => 'Hier liegt der Code deiner Anwendung.',
                'en_CH' => 'This is where the code of your application lives.',
            ],
            'provider' => 'GitHub',
            'vendor' => 'GitHub',
            'vendor_country' => 'US',
            'place' => '',
            'countries' => ['US'],
            'operation' => 'service',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://github.com',
                ],
            ],
        ],
        [
            'layer' => 'development',
            'key' => 'cicd',
            'order' => 2,
            'name' => [
                'de_CH' => 'CI/CD',
                'en_CH' => 'CI/CD',
            ],
            'text' => [
                'de_CH' => 'Jede Änderung am Code wird automatisch geprüft und danach ausgerollt. So kommt nur live, was funktioniert. Die Prüfungen laufen auf unseren eigenen Runnern bei uns im Haus.',
                'en_CH' => 'Every code change is checked automatically and then rolled out. Only what works goes live. The checks run on our own runners on our premises.',
            ],
            'provider' => 'GitHub Actions',
            'vendor' => 'GitHub',
            'vendor_country' => 'US',
            'place' => 'Region Basel',
            'countries' => ['CH'],
            'operation' => 'self',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://github.com/features/actions',
                ],
            ],
        ],
        [
            'layer' => 'backups',
            'key' => 'gitlab',
            'order' => 1,
            'name' => [
                'de_CH' => 'Onsite',
                'en_CH' => 'Onsite',
            ],
            'text' => [
                'de_CH' => 'Eine vollständige Kopie des Codes auf unserem eigenen GitLab. Von dort geht sie weiter ins Onsite-Backup.',
                'en_CH' => 'A complete copy of the code on our own GitLab. From there it moves on to the onsite backup.',
            ],
            'provider' => 'GitLab',
            'vendor' => 'GitLab',
            'vendor_country' => 'US',
            'place' => 'Region Basel',
            'countries' => ['CH'],
            'operation' => 'self',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://about.gitlab.com',
                ],
            ],
        ],
        [
            'layer' => 'backups',
            'key' => 'synon',
            'order' => 2,
            'name' => [
                'de_CH' => 'Onsite',
                'en_CH' => 'Onsite',
            ],
            'text' => [
                'de_CH' => 'Das Backup bei uns im Haus: Daten und Code landen auf derselben Synology.',
                'en_CH' => 'The backup on our premises: data and code end up on the same Synology.',
            ],
            'provider' => 'Synology',
            'vendor' => 'Synology',
            'vendor_country' => 'TW',
            'place' => 'Region Basel',
            'countries' => ['CH'],
            'operation' => 'self',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://www.synology.com',
                ],
            ],
        ],
        [
            'layer' => 'backups',
            'key' => 'synoff',
            'order' => 3,
            'name' => [
                'de_CH' => 'Offsite',
                'en_CH' => 'Offsite',
            ],
            'text' => [
                'de_CH' => 'Das Backup ausser Haus: Die Onsite-Sicherung wandert täglich an einen zweiten Standort. Fällt einer aus, bleibt der andere.',
                'en_CH' => 'The backup off our premises: the onsite backup moves to a second location every day. If one fails, the other remains.',
            ],
            'provider' => 'Synology',
            'vendor' => 'Synology',
            'vendor_country' => 'TW',
            'place' => 'Region Basel',
            'countries' => ['CH'],
            'operation' => 'self',
            'links' => [
                [
                    'type' => 'website',
                    'url' => 'https://www.synology.com',
                ],
            ],
        ],
    ];

    public function run(): void
    {
        $layers = InfrastructureLayer::query()->pluck('id', 'key');

        foreach (self::SERVICES as $service) {
            $layer = $service['layer'];
            unset($service['layer']);

            InfrastructureService::query()->updateOrCreate(
                ['key' => $service['key']],
                [...$service, 'infrastructure_layer_id' => $layers[$layer]],
            );
        }

        InfrastructureService::query()->whereNotIn('key', array_column(self::SERVICES, 'key'))->delete();
    }
}
