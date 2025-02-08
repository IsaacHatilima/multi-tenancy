<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('login screen can be rendered', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('No  2FA users can authenticate', function () {
    $user = User::factory()->create([
        'password' => Hash::make('Password1#'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'Password1#',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('No  2FA users can not authenticate with invalid email', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => 'invalid@email.com',
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('No  2FA users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});
