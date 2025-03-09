<?php

use App\Models\User;

test('user can enable 2FA', function () {
    $user = User::factory()->create([
        'password' => Hash::make('Password1#'),
    ]);

    $this->actingAs($user)->post('/user/confirm-password', [
        'password' => 'Password1#',
    ]);

    $response = $this->actingAs($user)->post('/user/two-factor-authentication');

    $user->refresh();

    $response->assertStatus(302);
    $this->assertNotNull($user->two_factor_secret);
    $this->assertNotNull($user->two_factor_recovery_codes);
    $this->assertNull($user->two_factor_confirmed_at);
});

test('user cannot enable 2FA with wrong password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('Password1#'),
    ]);

    $response = $this->actingAs($user)->post('/user/confirm-password', [
        'password' => 'Password12#',
    ]);

    $this->assertEquals(session('errors')->get('password')[0], 'The provided password was incorrect.');

    $user->refresh();

    $response->assertStatus(302);

    $this->assertNull($user->two_factor_secret);
    $this->assertNull($user->two_factor_recovery_codes);
    $this->assertNull($user->two_factor_confirmed_at);
});
