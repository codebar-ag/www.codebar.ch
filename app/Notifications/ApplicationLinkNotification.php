<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ApplicationLinkNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $url,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(Application $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your application at codebar'))
            ->greeting(__('Dear applicant'))
            ->line(__('This is your personal link to your application. It is valid for 7 days — after that, simply enter your email address again on the job page to receive a fresh one.'))
            ->action(__('Open my application'), $this->url)
            ->line(__('You can save your application as often as you like and come back later. If you did not request this link, you can ignore this email.'))
            ->line(__('We look forward to your application.'))
            ->salutation(new HtmlString('Sebastian Bürgin<br>codebar Solutions AG'));
    }

    /**
     * @return array<string, string>
     */
    public function toArray(Application $notifiable): array
    {
        return [
            'application_id' => (string) $notifiable->id,
            'url' => $this->url,
        ];
    }
}
