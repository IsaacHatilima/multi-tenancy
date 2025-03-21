<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Tenant $tenant): bool
    {
        return $user->tenant_id == $tenant->id;
    }

    public function view(User $user, Task $task, Tenant $tenant): bool
    {
        return $user->tenant_id == $tenant->id;
    }

    public function create(User $user, Task $task, Tenant $tenant): bool
    {
        return $user->tenant_id == $tenant->id && $user->role == 'admin';
    }

    public function update(User $user, Task $task, Tenant $tenant): bool
    {
        return $user->tenant_id == $tenant->id;
    }

    public function delete(User $user, Task $task, Tenant $tenant): bool
    {
        return $user->tenant_id == $tenant->id && $user->role == 'admin';
    }

    public function restore(User $user, Task $task, Tenant $tenant): bool
    {
        return $user->tenant_id == $tenant->id && $user->role == 'admin';
    }

    public function forceDelete(User $user, Task $task): bool {}
}
