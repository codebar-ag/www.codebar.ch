<?php

namespace App\Data;

use Illuminate\Support\Collection;

class GkiServiceData
{
    public static function all(): Collection
    {
        return collect([
            self::strategy(),
            self::sprint(),
            self::build(),
        ]);
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::all()->firstWhere('slug', $slug);
    }

    private static function strategy(): array
    {
        return [
            'slug' => 'gki-strategy',
            'name' => 'GKI Strategy',
            'teaser' => 'Strategische Einordnung von KI im Unternehmen.',
            'features' => [
                'KI-Readiness Assessment',
                'Identifikation priorisierter Use Cases',
                'Business Case & Wertschöpfungslogik',
                'Governance- und Compliance-Rahmen',
                'Roadmap (6–12 Monate)',
            ],
            'closing' => null,
            'audience' => 'Ideal für Geschäftsleitungen, Innovationsverantwortliche und Hochschulen.',
        ];
    }

    private static function sprint(): array
    {
        return [
            'slug' => 'gki-sprint',
            'name' => 'GKI Sprint',
            'teaser' => 'Vom Problem zum funktionierenden Prototyp in 2–5 Tagen.',
            'features' => [
                'Use-Case-Schärfung',
                'Prompt-Architektur',
                'MVP-Entwicklung (z.B. interner Copilot, Wissensagent, Automationslösung)',
                'Nutzer-Test',
                'Skalierungsentscheidung',
            ],
            'closing' => 'Kein PowerPoint. Nur funktionierende Systeme.',
            'audience' => null,
        ];
    }

    private static function build(): array
    {
        return [
            'slug' => 'gki-build',
            'name' => 'GKI Build',
            'teaser' => 'Technische Integration in bestehende Systeme.',
            'features' => [
                'API-Integration',
                'CRM- / ERP-Anbindung',
                'Interne Wissens-GPTs',
                'Automatisierungsstrecken',
                'Dokumentation & Betriebskonzept',
            ],
            'closing' => 'Wir bauen Lösungen, die produktiv laufen – nicht Demo-Umgebungen.',
            'audience' => null,
        ];
    }
}
