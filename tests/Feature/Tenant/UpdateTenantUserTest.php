<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

$data = [
    'first_name' => 'john',
    'last_name' => 'doe',
    'email' => 'johndoe@mail.com',
    'role' => 'admin',
];

test('tenant user can be updated', function ($data) {
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
            ->has('users')
        );

    $createdUser = User::where('email', $data['email'])->first();

    $this
        ->followingRedirects()
        ->put(route('users.update', $createdUser->id), [
            'email' => $data['email'],
            'first_name' => 'John Paul',
            'last_name' => 'Doe',
            'role' => 'user',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantPages/Users')
        );

    $this->assertDatabaseHas('users', ['email' => $data['email']]);
    $this->assertDatabaseHas('profiles', ['first_name' => 'John Paul']);
    tenancy()->end();
})->with([
    'data' => [$data],
]);

test('tenant user cannot be updated with missing fields', function ($data) {
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
            ->has('users')
        );

    $createdUser = User::where('email', $data['email'])->first();

    $this
        ->followingRedirects()
        ->put(route('users.update', $createdUser->id), [
            'email' => null,
            'first_name' => 'John Paul',
            'last_name' => 'Doe',
            'role' => 'user',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantPages/Users')
            ->has('errors')
            ->where('errors.email', 'E-Mail is required.')
        );
    tenancy()->end();
})->with([
    'data' => [$data],
]);
