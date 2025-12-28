<?php

namespace App\Policies;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactMessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, ContactMessage $contactMessage): bool
    {
    }

    public function delete(User $user, ContactMessage $contactMessage): bool
    {
    }

    public function restore(User $user, ContactMessage $contactMessage): bool
    {
    }

    public function forceDelete(User $user, ContactMessage $contactMessage): bool
    {
    }
}
