<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

$data = [
    'name' => 'TikTok',
    'address' => 'Ostheim',
    'city' => 'Regensburg',
    'state' => 'Bavaria',
    'country' => 'Germany',
    'zip' => '12345',
    'contact_first_name' => 'john',
    'contact_last_name' => 'doe',
    'contact_email' => 'user@mail.com',
    'contact_phone' => '49123456789',
    'status' => 'active', // active or in-active
];

test('tenant can be updated', function ($data) {
    $user = User::factory()->create(['email' => 'user@mail.com', 'password' => Hash::make('Password1#')]);

    $tenant = createTenant($user, 'central');

    $this->get(route('login'));

    $this
        ->followingRedirects()
        ->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
        );

    $this
        ->followingRedirects()
        ->get(route('tenants'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/Index')
            ->has('tenants')
        );

    $this
        ->followingRedirects()
        ->get(route('tenants.show', $tenant->id))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantDetails')
        );

    $updateData = $data;
    $updateData['name'] = 'New Name';
    $updateData['contact_first_name'] = 'changed';
    $updateData['contact_last_name'] = 'name';

    $this
        ->followingRedirects()
        ->put(route('tenants.update', $tenant->id), $updateData)
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantDetails')
        );

    $this->assertDatabaseHas('tenants', [
        'name' => 'NEW NAME',
        'contact_first_name' => 'Changed',
        'contact_last_name' => 'Name',
    ]);

})->with([
    'data' => [$data],
]);

test('tenant cannot be updated', function ($data) {
    $user = User::factory()->create(['email' => 'user@mail.com', 'password' => Hash::make('Password1#')]);

    $tenant = createTenant($user, 'central');

    $this->get(route('login'));

    $this
        ->followingRedirects()
        ->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
        );

    $this
        ->followingRedirects()
        ->get(route('tenants'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/Index')
            ->has('tenants')
        );

    $this
        ->followingRedirects()
        ->get(route('tenants.show', $tenant->id))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantDetails')
        );

    $updateData = $data;
    $updateData['name'] = null;
    $updateData['contact_first_name'] = 'changed';
    $updateData['contact_last_name'] = 'name';

    $this
        ->followingRedirects()
        ->put(route('tenants.update', $tenant->id), $updateData)
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantDetails')
            ->has('errors')
            ->where('errors.name', 'Tenant Name is required.')
        );

})->with([
    'data' => [$data],
]);
