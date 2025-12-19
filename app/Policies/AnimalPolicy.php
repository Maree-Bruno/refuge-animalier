<?php

namespace App\Policies;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnimalPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
       return $user->isAdmin() || $user->isVolunteer();
    }

    public function view(User $user, Animal $animal): bool
    {
        return $user->isAdmin() || $user->isVolunteer();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isVolunteer();
    }

    public function update(User $user, Animal $animal): bool
    {
        return $user->isAdmin() || $user->isVolunteer();
    }

    public function delete(User $user, Animal $animal): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Animal $animal): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Animal $animal): bool
    {
        return $user->isAdmin();
    }
    public function publish(User $user): bool
    {
        return $user->isAdmin();
    }
}
