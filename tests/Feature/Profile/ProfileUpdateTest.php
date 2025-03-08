<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('profile updates with same email', function () {
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

    $this->get(route('profile.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Edit')
            ->where('errors', [])
        );

    $this
        ->followingRedirects()
        ->patch(route('profile.update'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $user->email,
            'date_of_birth' => '1992-12-01',
            'gender' => 'male',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Edit')
            ->where('auth.user.email', $user->email)
            ->where('auth.user.profile.first_name', 'John')
            ->where('auth.user.profile.last_name', 'Doe')
            ->where('auth.user.profile.date_of_birth', '1992-12-01')
            ->where('auth.user.profile.gender', 'male')
        );

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('profile updated with new email', function () {
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

    $this->get(route('profile.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Edit')
            ->where('errors', [])
        );

    $this
        ->followingRedirects()
        ->patch(route('profile.update'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'new@email.com',
            'date_of_birth' => '1992-12-01',
            'gender' => 'male',
        ])
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Edit')
            ->where('auth.user.email', 'new@email.com')
            ->where('auth.user.profile.first_name', 'John')
            ->where('auth.user.profile.last_name', 'Doe')
            ->where('auth.user.profile.date_of_birth', '1992-12-01')
            ->where('auth.user.profile.gender', 'male')
        );

    $this->assertNull($user->refresh()->email_verified_at);
});
