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
    'contact_phone' => '+49123456789',
    'domain' => 'tiktok',
];

test('tenant can be created', function ($data) {
    $user = User::factory()->create(['email' => 'user@mail.com', 'password' => Hash::make('Password1#')]);

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
        ->post(route('tenants.store'), $data)
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/TenantDetails')
        );

    $this->assertDatabaseHas('tenants', ['name' => 'TIKTOK']);
})->with([
    'data' => [$data],
]);

test('tenant cannot be created with missing fields', function ($data) {
    $user = User::factory()->create(['email' => 'user@mail.com', 'password' => Hash::make('Password1#')]);

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

    $dataWithNullName = $data;
    $dataWithNullName['name'] = '';
    $dataWithNullName['contact_first_name'] = '';

    $this
        ->followingRedirects()
        ->post(route('tenants.store'), $dataWithNullName)
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/Index')
            ->has('errors')
            ->where('errors.name', 'Tenant Name is required.')
            ->where('errors.contact_first_name', 'Contact First Name is required.')
        );

    $this->assertDatabaseMissing('tenants', ['name' => 'TIKTOK']);
})->with([
    'data' => [$data],
]);
