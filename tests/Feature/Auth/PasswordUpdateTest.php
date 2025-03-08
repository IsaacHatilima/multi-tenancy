<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

test('password can be updated', function () {
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

    $this->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Security')
            ->where('errors', [])
        );

    $this
        ->followingRedirects()
        ->put(route('password.update'), [
            'current_password' => 'Password1#',
            'password' => 'Password12#',
            'password_confirmation' => 'Password12#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Security')
        );
});

test('correct password must be provided to update password', function () {
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

    $this->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Security')
            ->where('errors', [])
        );

    $this
        ->followingRedirects()
        ->put(route('password.update'), [
            'current_password' => 'InvalidPassword',
            'password' => 'Password12#',
            'password_confirmation' => 'Password12#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Security')
            ->where('errors.current_password', 'Current password is incorrect.')
        );
});
