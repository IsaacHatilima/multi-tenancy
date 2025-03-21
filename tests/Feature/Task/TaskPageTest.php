<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('task page can be rendered', function () {
    // This creates central user to create a tenant
    $centralUser = User::factory()->create(['email' => 'user@mail.com', 'password' => Hash::make('Password1#')]);

    createTenant($centralUser, 'tenant');

    $this->get(tenantUrl('login', tenant()->domain->domain));

    $this
        ->followingRedirects()
        ->post(tenantUrl('login.post', tenant()->domain->domain), [
            'email' => 'tenant@mail.com', // email for tenant user
            'password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
        );

    $this
        ->followingRedirects()
        ->get(route('tasks.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tasks/Index')
            ->has('tasks')
        );

    tenancy()->end();

});
