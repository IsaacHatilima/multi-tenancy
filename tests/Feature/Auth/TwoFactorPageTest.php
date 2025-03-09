<?php

use App\Models\User;

test('user can see security page', function () {
    $user = User::factory()->create(['password' => Hash::make('Password1#')]);

    $response = $this
        ->actingAs($user)
        ->get(route('security.edit'));

    $response->assertOk();
});

test('user must login to see security page', function () {
    $response = $this
        ->get(route('security.edit'));

    $response->assertStatus(302);
});
