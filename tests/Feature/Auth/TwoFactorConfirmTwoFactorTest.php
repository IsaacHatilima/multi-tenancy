<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use PragmaRX\Google2FA\Google2FA;

test('user can confirm 2FA', function () {
    $user = User::factory()->create([
        'password' => Hash::make('Password1#'),
    ]);

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
        ->put(route('enable.fortify'), [
            'current_password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Security')
        );

    $user->refresh();

    $decryptedSecret = Crypt::decrypt($user->two_factor_secret);

    $google2fa = new Google2FA;
    $otp = $google2fa->getCurrentOtp($decryptedSecret);

    $this
        ->followingRedirects()
        ->put(route('confirm.fortify'), [
            'code' => $otp,
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Security')
            ->whereNot('auth.user.two_factor_secret', null)
            ->whereNot('auth.user.two_factor_recovery_codes', null)
            ->whereNot('auth.user.two_factor_confirmed_at', null)
        );
});

test('user cannot confirm 2FA with wrong password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('Password1#'),
    ]);

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
        ->put(route('enable.fortify'), [
            'current_password' => 'Password1#',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Security')
        );

    $this
        ->followingRedirects()
        ->put(route('confirm.fortify'), [
            'code' => '123987',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Security')
            ->whereNot('auth.user.two_factor_secret', null)
            ->whereNot('auth.user.two_factor_recovery_codes', null)
            ->where('auth.user.two_factor_confirmed_at', null)
        );
});
