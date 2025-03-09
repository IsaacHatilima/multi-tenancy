<?php

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

test('user can de-activate 2FA', function () {
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

    $this->actingAs($user)->post('/user/confirmed-two-factor-authentication', [
        'code' => $otp,
    ]);

    $response = $this->actingAs($user)->delete('/user/two-factor-authentication');

    $user->refresh();

    $response->assertStatus(302);
    $this->assertNull($user->two_factor_secret);
    $this->assertNull($user->two_factor_recovery_codes);
    $this->assertNull($user->two_factor_confirmed_at);
});

test('user cannot de-activate 2FA with wrong password', function () {
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

    $this->actingAs($user)->post('/user/confirmed-two-factor-authentication', [
        'code' => $otp,
    ]);

    $this->actingAs($user)->post('/user/confirm-password', [
        'password' => 'Password12#',
    ]);

    if (session()->has('errors') && session('errors')->has('password')) {
        $this->assertEquals(
            session('errors')->get('password')[0],
            'The provided password was incorrect.'
        );
    } else {
        $response = $this->actingAs($user)->delete('/user/two-factor-authentication');
        $response->assertStatus(302);
    }

    $user->refresh();

    $this->assertNotNull($user->two_factor_secret);
    $this->assertNotNull($user->two_factor_recovery_codes);
    $this->assertNotNull($user->two_factor_confirmed_at);
});
