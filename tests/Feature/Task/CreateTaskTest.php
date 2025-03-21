<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('task can be created', function () {
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

    $this
        ->followingRedirects()
        ->post(route('tasks.store'), [
            'assigned_to' => $assignedUser->id,
            'priority' => 'low',
            'escalation' => '',
            'status' => 'pending',
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

    $this->assertDatabaseHas('tasks', ['title' => 'Awesome Title']);

    tenancy()->end();

});

test('task cannot be created with missing required fields', function () {
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

    $this
        ->followingRedirects()
        ->post(route('tasks.store'), [
            'assigned_to' => $assignedUser->id,
            'priority' => '',
            'escalation' => '',
            'status' => '',
            'title' => 'Awesome Title',
            'description' => 'This is a very detailed description.',
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d', strtotime('+5 days')),
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tasks/Index')
            ->has('errors')
            ->where('errors.priority', 'Task should be assigned a priority.')
            ->where('errors.status', 'Task should be assigned a status.')
        );

    tenancy()->end();

});
