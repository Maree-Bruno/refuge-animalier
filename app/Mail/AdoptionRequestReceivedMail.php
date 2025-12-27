<?php

namespace App\Mail;

use App\Models\AdoptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdoptionRequestReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AdoptionRequest $adoptionRequest
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Demande d\'adoption bien reçue',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.adoption-request-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
