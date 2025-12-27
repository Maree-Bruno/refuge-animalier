<?php

namespace App\Notifications;

use App\Models\AdoptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdoptionRequestCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public AdoptionRequest $adoptionRequest
    )
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $animal = $this->adoptionRequest->animal;

        return (new MailMessage)
            ->subject('Nouvelle demande d\'adoption - ' . $animal->name)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle demande d\'adoption a été soumise.')
            ->line('**Animal concerné :** ' . $animal->name)
            ->line('**Adoptant :** ' . $this->adoptionRequest->adopter->name)
            ->line('**Email :** ' . $this->adoptionRequest->adopter->email)
            ->line('**Téléphone :** ' . $this->adoptionRequest->adopter->phone)
            ->line('**Message :**')
            ->line($this->adoptionRequest->message)
            ->action('Voir la demande', url('/adoption'))
            ->line('Merci de traiter cette demande dans les meilleurs délais.');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
