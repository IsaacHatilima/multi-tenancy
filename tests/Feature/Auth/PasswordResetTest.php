<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;

test('reset password link screen can be rendered', function () {
    $this->get(route('password.request'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/ForgotPassword')
            ->where('errors', [])
        );
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create(['email' => 'johndoe@example.com']);

    $this->get(route('password.request'));

    $this
        ->followingRedirects()
        ->post(route('password.email'), [
            'email' => $user->email,
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/ForgotPassword')
            ->where('status', 'Password reset link sent. Please check your email.')
        );

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this
        ->post(route('password.email'), [
            'email' => $user->email,
        ]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
        $token = $notification->token;

        return true;
    });

    $this->get(route('password.reset', ['token' => $token.'?email='.$user->email]))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/ResetPassword')
            ->where('errors', [])
        );
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this
        ->post(route('password.email'), [
            'email' => $user->email,
        ]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
        $token = $notification->token;

        return true;
    });

    $this->get(route('password.reset', ['token' => $token.'?email='.$user->email]))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/ResetPassword')
            ->where('errors', [])
        );

    $this
        ->followingRedirects()
        ->post(route('password.store', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Password1#',
            'password_confirmation' => 'Password1#',
        ]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Auth/Login')
            ->where('status', 'Your password has been reset.')
        );
});
