<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('Email 2FA can be toggled', function () {
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
        ->put(route('email.fa', 'custom'), [
            'current_password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Security')
            ->where('auth.user.two_factor_type', 'custom')
        );
});

test('Email 2FA cannot be toggled with invalid password', function () {
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
        ->put(route('email.fa', 'custom'), [
            'current_password' => 'Password123#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Security')
            ->has('errors')
            ->where('errors.current_password', 'Current password is incorrect.')
        );
});
