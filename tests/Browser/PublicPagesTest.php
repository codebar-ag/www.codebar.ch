<?php

declare(strict_types=1);

it('renders the page under the content security policy without JavaScript errors', function (string $routeName, string $heading) {
    visit(route($routeName, absolute: false))
        ->assertSeeIn('h1:visible', $heading)
        ->assertNoJavaScriptErrors()
        ->assertNoConsoleLogs();
})->with([
    'start de' => ['de-ch.start.index', 'Schön, bist du da'],
    'start en' => ['en-ch.start.index', 'Good to have you here'],
    'news' => ['de-ch.news.index', 'Neuigkeiten'],
    'team' => ['de-ch.about-us.index', 'Team'],
    'services' => ['de-ch.services.index', 'Expertise'],
    'ai' => ['de-ch.ai.index', 'KI bei codebar'],
    'llm' => ['de-ch.ai.llm.index', 'Unsere lokalen LLMs im Einsatz'],
    'llm analytics' => ['de-ch.ai.llm.analytics.index', 'LLM-Nutzungsstatistik'],
    'network' => ['de-ch.network.index', 'Netzwerk'],
    'network request' => ['de-ch.network.request.index', 'Netzwerk-Profil aktualisieren'],
    'jobs' => ['de-ch.jobs.index', 'Stellen'],
    'internship' => ['de-ch.jobs.internship.show', 'Praktikum'],
    'media' => ['de-ch.media.index', 'Medien'],
    'contact' => ['de-ch.contact.index', 'Kontakt'],
    'imprint' => ['de-ch.legal.imprint.index', 'Impressum'],
    'privacy' => ['de-ch.legal.privacy.index', 'Datenschutz'],
    'terms' => ['de-ch.legal.terms.index', 'Allgemeine Geschäftsbedingungen'],
]);

it('redirects the domain root to the German start page', function () {
    visit('/')
        ->assertPathIs('/de-ch')
        ->assertSeeIn('h1:visible', 'Schön, bist du da')
        ->assertNoJavaScriptErrors();
});

it('shows the error page for an unknown path', function () {
    visit('/diese-seite-gibt-es-nicht')
        ->assertSee(__('errors.title_client', [], 'de_CH'))
        ->assertSee(__('errors.back_home', [], 'de_CH'))
        ->assertNoJavaScriptErrors();
});
