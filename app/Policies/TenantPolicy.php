<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {}

    public function view(User $user, Tenant $tenant): bool {}

    public function create(User $user): bool {}

    public function update(User $user, Tenant $tenant): bool {}

    public function delete(User $user, Tenant $tenant): bool {}

    public function restore(User $user, Tenant $tenant): bool {}

    public function forceDelete(User $user, Tenant $tenant): bool {}
}
