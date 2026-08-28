<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\ApplicationFile;
use App\Notifications\ApplicationLinkNotification;
use App\Notifications\ApplicationReceivedNotification;
use App\Notifications\ApplicationSubmittedNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Storage;

it('renders the internal notification with all answers, links and documents', function () {
    Storage::fake('s3');

    $application = Application::factory()->create([
        'first_name' => 'Mina',
        'last_name' => 'Keller',
        'age' => 16,
        'city' => 'Basel',
        'email' => 'mina@example.com',
        'interests' => 'Web-Entwicklung und Open Source.',
        'focus_fit' => 'Laravel klingt spannend.',
        'built_so_far' => 'Eine kleine Website.',
        'about' => 'Ich spiele Handball.',
        'github' => 'https://github.com/mina',
        'linkedin' => 'https://www.linkedin.com/in/mina-keller',
        'project_link' => 'https://mina.example',
    ]);

    ApplicationFile::factory()->create([
        'application_id' => $application->id,
        'original_name' => 'lebenslauf.pdf',
    ]);

    $html = (string) (new ApplicationReceivedNotification($application->load('files')))
        ->toMail(new AnonymousNotifiable)
        ->render();

    expect($html)
        ->toContain('Mina Keller')
        ->toContain('16')
        ->toContain('Basel')
        ->toContain('mina@example.com')
        ->toContain('https://github.com/mina')
        ->toContain('https://www.linkedin.com/in/mina-keller')
        ->toContain('Web-Entwicklung und Open Source.')
        ->toContain('Laravel klingt spannend.')
        ->toContain('Eine kleine Website.')
        ->toContain('Ich spiele Handball.')
        ->toContain('lebenslauf.pdf');
})->group('applications');

it('renders the link mail fully in german without a formal salutation', function () {
    app()->setLocale('de_CH');

    $application = Application::factory()->create();

    $html = (string) (new ApplicationLinkNotification('https://example.com/link'))
        ->toMail($application)
        ->render();

    expect($html)
        ->toContain('Liebe:r Bewerber:in')
        ->toContain('Das ist dein persönlicher Link')
        ->toContain('Wir freuen uns auf deine Bewerbung.')
        ->toContain('Falls sich der Button')
        ->toContain('Alle Rechte vorbehalten.')
        ->toContain('Sebastian Bürgin');

    expect(str_contains($html, 'Liebe Grüsse'))->toBeFalse();
    expect(str_contains($html, 'having trouble'))->toBeFalse();
    expect(str_contains($html, 'Praktikum'))->toBeFalse();
})->group('applications');

it('renders the link mail in english on the english site', function () {
    app()->setLocale('en_CH');

    $application = Application::factory()->create();

    $html = (string) (new ApplicationLinkNotification('https://example.com/link'))
        ->toMail($application)
        ->render();

    expect($html)
        ->toContain('Dear applicant')
        ->toContain('This is your personal link')
        ->toContain('We look forward to your application.');

    expect(str_contains($html, 'persönlicher Link'))->toBeFalse();
})->group('applications');

it('renders the confirmation mail without a formal salutation', function () {
    app()->setLocale('de_CH');

    $application = Application::factory()->create(['first_name' => 'Mina']);

    $html = (string) (new ApplicationSubmittedNotification('https://example.com/link'))
        ->toMail($application)
        ->render();

    expect($html)
        ->toContain('Danke für deine Bewerbung')
        ->toContain('Sebastian Bürgin');

    expect(str_contains($html, 'Liebe Grüsse'))->toBeFalse();
})->group('applications');
