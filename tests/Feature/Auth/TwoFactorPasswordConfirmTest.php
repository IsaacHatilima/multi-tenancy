<?php

use App\Models\User;

test('user can confirm password', function () {
    $user = User::factory()->create(['password' => Hash::make('Password1#')]);

    $response = $this->actingAs($user)->post('/user/confirm-password', [
        'password' => 'Password1#',
    ]);

    $response->assertStatus(302);
});

test('user password confirmation fails', function () {
    $user = User::factory()->create(['password' => Hash::make('Password1#')]);

    $response = $this->actingAs($user)->post('/user/confirm-password', [
        'password' => 'Password12#',
    ]);

    $response->assertStatus(302);
    $this->assertEquals(session('errors')->get('password')[0], 'The provided password was incorrect.');
});
