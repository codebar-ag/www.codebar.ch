<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OdooPricesChangedNotification extends Notification
{
    /**
     * @param  array<int, string>  $findings
     */
    public function __construct(
        public array $findings,
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
        $message = (new MailMessage)
            ->subject('Odoo-Preise: config/odoo-pricing.php prüfen')
            ->line('Die Preisprüfung für die Odoo-Preisseite hat Abweichungen gefunden:');

        foreach ($this->findings as $finding) {
            $message->line('• '.$finding);
        }

        return $message
            ->line('Bitte die Werte in config/odoo-pricing.php und das Datum checked_at anpassen.')
            ->action('Preisseite öffnen', route('de-ch.erp.systems.pricing.show'));
    }
}
