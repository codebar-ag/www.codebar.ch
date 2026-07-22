<?php

namespace App\Http\Controllers\Gki;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GkiIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.gki.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'gki.index'))->default(),
            'tiers' => $this->tiers(),
        ]);
    }

    /**
     * @return array<int, array{name: string, teaser: string, features: array<int, string>, closing: string|null, audience: string|null}>
     */
    private function tiers(): array
    {
        return [
            [
                'name' => 'GKI Strategy',
                'teaser' => __('Strategic positioning of AI in your organisation.'),
                'features' => [
                    __('AI readiness assessment'),
                    __('Identification of prioritised use cases'),
                    __('Business case & value creation logic'),
                    __('Governance and compliance framework'),
                    __('Roadmap (6–12 months)'),
                ],
                'closing' => null,
                'audience' => __('Ideal for executive boards, innovation leads and universities.'),
            ],
            [
                'name' => 'GKI Sprint',
                'teaser' => __('From problem to working prototype in 2–5 days.'),
                'features' => [
                    __('Use-case refinement'),
                    __('Prompt architecture'),
                    __('MVP development (e.g. internal copilot, knowledge agent, automation solution)'),
                    __('User testing'),
                    __('Scaling decision'),
                ],
                'closing' => __('No PowerPoint. Only working systems.'),
                'audience' => null,
            ],
            [
                'name' => 'GKI Build',
                'teaser' => __('Technical integration into existing systems.'),
                'features' => [
                    __('API integration'),
                    __('CRM / ERP integration'),
                    __('Internal knowledge GPTs'),
                    __('Automation pipelines'),
                    __('Documentation & operating concept'),
                ],
                'closing' => __('We build solutions that run in production — not demo environments.'),
                'audience' => null,
            ],
        ];
    }
}
