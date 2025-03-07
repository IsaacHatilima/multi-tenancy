<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

/*
 * Default user password in User factory is: Password1#
 * */

test('login screen renders correct Inertia page', function () {
    $this->get(route('login'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
            ->where('errors', [])
        );
});

test('user can login', function () {
    $user = User::factory()->create();

    $this->get(route('login'));

    $this
        ->followingRedirects()
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('auth.user', auth()->user())
        );
});

test('users can not authenticate with invalid email', function () {
    User::factory()->create();

    $this->get(route('login'));

    $this
        ->followingRedirects()
        ->post(route('login'), [
            'email' => 'invalid@email.com',
            'password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
            ->where('errors.email', 'Invalid E-mail or Password provided.')
        );
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->get(route('login'));

    $this
        ->followingRedirects()
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'Password12#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
            ->where('errors.email', 'Invalid E-mail or Password provided.')
        );
});
