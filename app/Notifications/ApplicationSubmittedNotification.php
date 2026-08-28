<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ApplicationSubmittedNotification extends Notification
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
            ->subject(__('We received your application'))
            ->greeting(__('Hello :name', ['name' => $notifiable->name()]))
            ->line(__('Thank you for your application at codebar — we will get back to you personally.'))
            ->line(__('You can view your submitted application at any time via your personal link.'))
            ->action(__('Open my application'), $this->url)
            ->salutation(new HtmlString('Sebastian Bürgin<br>codebar Solutions AG'));
    }

    /**
     * @return array<string, string>
     */
    public function toArray(Application $notifiable): array
    {
        return [
            'application_id' => (string) $notifiable->id,
        ];
    }
}
