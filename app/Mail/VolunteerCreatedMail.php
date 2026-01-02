<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VolunteerCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $plainPassword
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->user->email],
            subject: 'Bienvenue - Vos identifiants de connexion',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.volunteer-created',
            with: [
                'user' => $this->user,
                'password' => $this->plainPassword,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
