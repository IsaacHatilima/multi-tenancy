<?php

use Inertia\Testing\AssertableInertia as Assert;

test('registration screen can be rendered', function () {

    $this->get(route('register'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Register')
            ->where('errors', [])
        );
});

test('new users can register', function () {
    $this->get(route('register'));

    $this
        ->followingRedirects()
        ->post(route('register.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'password' => 'Password1#',
            'password_confirmation' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('auth.user.email', 'test@example.com')
        );
});

test('user registration fails', function () {
    $this->get(route('register'));

    $this
        ->followingRedirects()
        ->post(route('register.store'), [
            'first_name' => '',
            'last_name' => '',
            'email' => 'invalid-email',
            'password' => 'invalid-password',
            'password_confirmation' => 'invalid-password-mismatch',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Register')
            ->has('errors')
            ->where('errors.first_name', 'First Name is required.')
            ->where('errors.last_name', 'Last Name is required.')
            ->where('errors.email', 'Invalid email.')
            ->where('errors.password', 'Password must contain at least one number and one uppercase and lowercase letter.')
            ->where('errors.password_confirmation', 'Confirm Password does not match.')
        );
});
