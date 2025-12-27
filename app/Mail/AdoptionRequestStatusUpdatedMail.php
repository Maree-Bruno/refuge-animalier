<?php

namespace App\Mail;

use App\Models\AdoptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdoptionRequestStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AdoptionRequest $adoptionRequest
    ) {}

    public function envelope(): Envelope
    {
        $statusLabels = [
            'pending' => 'en cours d\'examen',
            'accepted' => 'acceptée',
            'rejected' => 'refusée'
        ];

        $status = $statusLabels[$this->adoptionRequest->status] ?? $this->adoptionRequest->status;

        return new Envelope(
            subject: "Votre demande d'adoption a été {$status}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.adoption-request-status-updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
