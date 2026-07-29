<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\NetworkUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NetworkInviteNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $url,
        public string $company,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(NetworkUser $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your profile in the codebar network'))
            ->greeting(__('Hello :name', ['name' => $notifiable->name]))
            ->line(__('Our partnership with :company is carried by people — first and foremost you. That is why our website shows not just companies but also the people behind them: if you like, you can be part of it in person, with your own profile.', ['company' => $this->company]))
            ->line(__('Use the link below to create and manage your profile — it only goes live once you publish it. The link is valid for 96 hours.'))
            ->action(__('Manage my profile'), $this->url)
            ->line(__('You can request a new link at any time: enter your email address under "Network → Profile" in the website footer.'))
            ->salutation(new HtmlString(e(__('Kind regards')).'<br>Sebastian Bürgin<br>codebar Solutions AG'));
    }

    /**
     * @return array<string, string>
     */
    public function toArray(NetworkUser $notifiable): array
    {
        return [
            'network_user_id' => (string) $notifiable->id,
            'url' => $this->url,
        ];
    }
}
