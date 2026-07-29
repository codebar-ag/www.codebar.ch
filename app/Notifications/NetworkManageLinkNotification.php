<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\NetworkUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NetworkManageLinkNotification extends Notification
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

    public function toMail(NetworkUser $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Update your codebar network profile'))
            ->greeting(__('Hello :name', ['name' => $notifiable->name]))
            ->line(__('You requested a link to update your profile in the codebar network. The link is valid for one hour and only applies to your own profile.'))
            ->action(__('Update my profile'), $this->url)
            ->line(__('If you did not request this link, you can ignore this email.'))
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
