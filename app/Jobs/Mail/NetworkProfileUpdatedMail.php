<?php

namespace App\Jobs\Mail;

use App\Models\NetworkUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NetworkProfileUpdatedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public NetworkUser $networkUser,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Network profile updated: :name', ['name' => $this->networkUser->name]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.network.profile-updated',
        );
    }
}
