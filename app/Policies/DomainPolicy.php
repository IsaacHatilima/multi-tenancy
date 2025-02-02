<?php

namespace App\Policies;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {}

    public function view(User $user, Domain $domain): bool {}

    public function create(User $user): bool {}

    public function update(User $user, Domain $domain): bool {}

    public function delete(User $user, Domain $domain): bool {}

    public function restore(User $user, Domain $domain): bool {}

    public function forceDelete(User $user, Domain $domain): bool {}
}
