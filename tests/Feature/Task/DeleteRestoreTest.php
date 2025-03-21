<?php

use App\Models\Task;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('task can be deleted', function () {
    $centralUser = User::factory()->create(['email' => 'user@mail.com', 'password' => Hash::make('Password1#')]);

    createTenant($centralUser, 'tenant');

    $this->get(tenantUrl('login', tenant()->domain->domain));

    $this
        ->followingRedirects()
        ->post(tenantUrl('login.post', tenant()->domain->domain), [
            'email' => 'tenant@mail.com',
            'password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
        );

    $this
        ->followingRedirects()
        ->get(tenantUrl('tasks.index', tenant()->domain->domain))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tasks/Index')
            ->has('tasks')
        );

    $assignedUser = User::first();
    $createdBy = User::firstWhere('email', 'tenant@mail.com');

    $task = Task::factory()->create(['assigned_to' => $assignedUser->id, 'user_id' => $createdBy->id]);

    $this
        ->followingRedirects()
        ->delete(route('tasks.destroy', $task))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tasks/Index')
            ->has('tasks')
        );

    $this->assertDatabaseMissing('tasks', ['deleted_at' => null]);

    tenancy()->end();

});

test('task can be restored', function () {
    $centralUser = User::factory()->create(['email' => 'user@mail.com', 'password' => Hash::make('Password1#')]);

    createTenant($centralUser, 'tenant');

    $this->get(tenantUrl('login', tenant()->domain->domain));

    $this
        ->followingRedirects()
        ->post(tenantUrl('login.post', tenant()->domain->domain), [
            'email' => 'tenant@mail.com',
            'password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
        );

    $this
        ->followingRedirects()
        ->get(tenantUrl('tasks.index', tenant()->domain->domain))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tasks/Index')
            ->has('tasks')
        );

    $assignedUser = User::first();
    $createdBy = User::firstWhere('email', 'tenant@mail.com');

    $task = Task::factory()->create(['assigned_to' => $assignedUser->id, 'user_id' => $createdBy->id]);

    $this
        ->followingRedirects()
        ->patch(route('tasks.restore', $task->id))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tasks/Index')
            ->has('tasks')
        );

    $this->assertDatabaseHas('tasks', ['deleted_at' => null]);

    tenancy()->end();

});
