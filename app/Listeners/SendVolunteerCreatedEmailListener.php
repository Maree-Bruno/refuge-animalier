<?php

namespace App\Listeners;

use App\Events\VolunteerCreatedEvent;
use App\Mail\VolunteerCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendVolunteerCreatedEmailListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(VolunteerCreatedEvent $event): void
    {
        Mail::to($event->user->email)->send(
            new VolunteerCreatedMail($event->user, $event->plainPassword)
        );
    }
}
