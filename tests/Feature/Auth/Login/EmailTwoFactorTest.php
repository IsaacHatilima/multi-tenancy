<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('Email 2FA page can be rendered', function () {
    $this->get(route('login.email.two.factor'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/EmailTwoFactor')
            ->where('errors', [])
        );
});

test('user can login', function () {
    $user = User::factory()->create([
        'two_factor_type' => 'custom',
    ]);

    $this->get(route('login'));

    /*
     * Make call to check type of auth to use. expect is custom
     * generates code and expires time and emails code
     * */
    $this
        ->followingRedirects()
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/EmailTwoFactor')
        );

    $this->get(route('login.email.two.factor'));

    // Get 2FA Code
    $twoFACode = User::where('id', $user->id)->first()->two_factor_code;

    // Submit 2FA code to login
    $this
        ->followingRedirects()
        ->post(route('login.email.two.post'), [
            'code' => $twoFACode,  // code sent to email from above request
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('auth.user', auth()->user())
        );
});

test('user cannot login with invalid email or password', function () {
    $user = User::factory()->create([
        'two_factor_type' => 'custom',
    ]);

    $this->get(route('login'));

    /*
     * Make call to check type of auth to use. expect is custom
     * generates code and expires time and emails code
     * */
    $this
        ->followingRedirects()
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'Password1234#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
            ->has('errors')
            ->where('errors.email', 'Invalid E-mail or Password provided.')
        );
});

test('user cannot login with invalid code', function () {
    $user = User::factory()->create([
        'two_factor_type' => 'custom',
    ]);

    $this->get(route('login'));

    /*
     * Make call to check type of auth to use. expect is custom
     * generates code and expires time and emails code
     * */
    $this
        ->followingRedirects()
        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/EmailTwoFactor')
        );

    $this->get(route('login.email.two.factor'));

    // Submit 2FA code to login
    $this
        ->followingRedirects()
        ->post(route('login.email.two.post'), [
            'code' => '001110',  // code sent to email from above request
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/EmailTwoFactor')
            ->has('errors')
            ->where('errors.code', 'Invalid or Expired code provided.')
        );
});

test('request new code', function () {

    $user = User::factory()->create();

    $this->get(route('login.email.two.factor'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/EmailTwoFactor')
            ->where('errors', [])
        );

    session(['email' => $user->email]);

    $this
        ->followingRedirects()
        ->get(route('new.two.factor.code'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/EmailTwoFactor')
            ->where('errors', [])
        );
});
