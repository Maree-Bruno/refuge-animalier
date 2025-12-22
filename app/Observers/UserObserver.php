<?php

namespace App\Observers;

use App\Events\VolunteerCreatedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserObserver
{
    private ?string $temporaryPassword = null;

    public function creating(User $user): void
    {
        if ($user->role === 'volunteer' && empty($user->password)) {
            $this->temporaryPassword = Str::random(12);
            $user->password = Hash::make($this->temporaryPassword);
        }
    }

    public function created(User $user): void
    {
        if ($user->role === 'volunteer' && $this->temporaryPassword !== null) {
            event(new VolunteerCreatedEvent($user, $this->temporaryPassword));
            $this->temporaryPassword = null;
        }
    }

    public function deleted(User $user): void
    {
        if ($user->picture) {
            Storage::disk(config('images.disk'))->delete($user->picture);
        }
    }
}
