<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

$data = [
    'first_name' => 'john',
    'last_name' => 'doe',
    'email' => 'johndoe@mail.com',
    'role' => 'admin',
];

test('tenant user can be created', function ($data) {
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
        ->get(route('users'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantPages/Users')
            ->has('users')
        );

    $this
        ->followingRedirects()
        ->post(route('users.store'), $data)
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantPages/Users')
        );

    $this->assertDatabaseHas('users', ['email' => $data['email']]);
    tenancy()->end();
})->with([
    'data' => [$data],
]);

test('tenant user with missing fields cannot be created', function ($data) {
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
        ->get(route('users'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantPages/Users')
            ->has('users')
        );

    $dataWithNullName = $data;
    $dataWithNullName['first_name'] = '';
    $dataWithNullName['email'] = '';

    $this
        ->followingRedirects()
        ->post(route('users.store'), $dataWithNullName)
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantPages/Users')
            ->has('users')
            ->has('errors')
            ->where('errors.email', 'Email is required.')
        );
    tenancy()->end();
})->with([
    'data' => [$data],
]);
