<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Application $application,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $application = $this->application;

        $mail = (new MailMessage)
            ->subject('Neue Bewerbung IMS-Praktikum: '.$application->name())
            ->greeting('Neue Bewerbung eingereicht')
            ->line("**Name:** {$application->name()}")
            ->line("**Alter:** {$application->age}")
            ->line("**Wohnort:** {$application->city}")
            ->line("**E-Mail:** {$application->email}");

        if ($application->github) {
            $mail->line("**GitHub:** {$application->github}");
        }

        if ($application->linkedin) {
            $mail->line("**LinkedIn:** {$application->linkedin}");
        }

        if ($application->project_link) {
            $mail->line("**Link:** {$application->project_link}");
        }

        $sections = [
            'Welche Bereiche der IT interessieren dich?' => $application->interests,
            'Was spricht dich an unserem Fokus an — und was eher nicht?' => $application->focus_fit,
            'Hast du schon etwas gebaut oder ausprobiert?' => $application->built_so_far,
            'Erzähl uns von dir' => $application->about,
        ];

        foreach ($sections as $title => $body) {
            if (blank($body)) {
                continue;
            }

            $mail->line("---\n\n**{$title}**")->line($body);
        }

        $files = $application->files;

        if ($files->isNotEmpty()) {
            $mail->line('---')->line('**Dokumente** (Links 7 Tage gültig):');

            foreach ($files as $file) {
                $url = rescue(fn (): string => $file->temporaryUrl(), report: false);

                $mail->line($url
                    ? "- [{$file->original_name}]({$url}) ({$file->humanSize()})"
                    : "- {$file->original_name} ({$file->humanSize()})");
            }
        }

        return $mail->salutation(' ');
    }

    /**
     * @return array<string, string>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => (string) $this->application->id,
        ];
    }
}
