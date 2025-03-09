<?php

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

test('user can confirm 2FA', function () {
    $user = User::factory()->create([
        'password' => Hash::make('Password1#'),
    ]);

    $this->actingAs($user)->post('/user/confirm-password', [
        'password' => 'Password1#',
    ]);

    $this->actingAs($user)->post('/user/two-factor-authentication');

    $decryptedSecret = Crypt::decrypt($user->two_factor_secret);

    $google2fa = new Google2FA;
    $otp = $google2fa->getCurrentOtp($decryptedSecret);

    $response = $this->actingAs($user)->post('/user/confirmed-two-factor-authentication', [
        'code' => $otp,
    ]);

    $user->refresh();

    $response->assertStatus(302);
    $this->assertEquals(session('status'), 'two-factor-authentication-confirmed');
    $this->assertNotNull($user->two_factor_confirmed_at);
});

test('user cannot confirm 2FA with wrong password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('Password1#'),
    ]);

    $this->actingAs($user)->post('/user/confirm-password', [
        'password' => 'Password1#',
    ]);

    $this->actingAs($user)->post('/user/two-factor-authentication');

    $response = $this->actingAs($user)->post('/user/confirmed-two-factor-authentication', [
        'code' => '124578',
    ]);

    $user->refresh();

    $response->assertStatus(302);
    $this->assertNull(session('status'));
    $this->assertNull($user->two_factor_confirmed_at);
});
