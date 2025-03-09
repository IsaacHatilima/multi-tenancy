<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

test('tenant can be deleted', function ($data) {
    $dataToCreateWith = $data;
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
        ->delete(route('tenants.destroy', $tenant->id), [
            'current_password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/Index')
        );

    $this->assertDatabaseMissing('tenants', ['name' => 'TIKTOK']);
})->with([
    'data' => [$data],
]);

test('tenant cannot be delete with wrong password', function ($data) {
    $dataToCreateWith = $data;
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
        ->delete(route('tenants.destroy', $tenant->id), [
            'current_password' => 'Password123#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Tenant/Index')
        );

    $this->assertDatabaseMissing('tenants', ['name' => 'TIKTOK']);
})->with([
    'data' => [$data],
]);
