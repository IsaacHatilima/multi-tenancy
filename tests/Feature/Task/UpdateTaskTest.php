<?php

use App\Models\Task;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('task can be updated', function () {
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
        ->put(route('tasks.update', $task->id), [
            'assigned_to' => $assignedUser->id,
            'priority' => 'low',
            'escalation' => 'level1',
            'status' => 'in_progress',
            'title' => 'Awesome Title',
            'description' => 'This is a very detailed description.',
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d', strtotime('+5 days')),
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tasks/Index')
            ->has('tasks')
        );

    $this->assertDatabaseHas('tasks', ['escalation' => 'level1']);

    tenancy()->end();

});

test('task cannot be updated', function () {
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
        ->put(route('tasks.update', $task->id), [
            'assigned_to' => '',
            'priority' => 'low',
            'escalation' => 'level1',
            'status' => 'in_progress',
            'title' => 'Awesome Title',
            'description' => 'This is a very detailed description.',
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d', strtotime('+5 days')),
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tasks/Index')
            ->has('errors')
            ->where('errors.assigned_to', 'Task should be assigned to a user.')
        );

    tenancy()->end();

});
