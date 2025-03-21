<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

$data = [
    'first_name' => 'john',
    'last_name' => 'doe',
    'email' => 'johndoe@mail.com',
];

test('tenant user can be deleted with valid password', function ($data) {
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

    $createdUser = User::where('email', 'tenant@mail.com')->first();

    $this
        ->followingRedirects()
        ->delete(route('users.destroy', $createdUser->id), [
            'current_password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantPages/Users')
        );

    $this->assertDatabaseMissing('users', ['email' => $createdUser->email]);
    tenancy()->end();
})->with([
    'data' => [$data],
]);

test('tenant user cannot be deleted with invalid password', function ($data) {
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

    $createdUser = User::where('email', 'tenant@mail.com')->first();

    $this
        ->followingRedirects()
        ->delete(route('users.destroy', $createdUser->id), [
            'current_password' => 'Password12#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantPages/Users')
            ->has('errors')
            ->where('errors.current_password', 'Current password is incorrect.')
        );
    tenancy()->end();
})->with([
    'data' => [$data],
]);
