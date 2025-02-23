<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('tenant can be delete', function () {
    $user = User::factory()->create([
        'password' => Hash::make('Password1#'),
    ]);
    $tenant = Tenant::factory()->create();

    $this->actingAs($user);

    $response = $this
        ->actingAs($user)
        ->delete(route('tenants.destroy', $tenant->id), [
            'current_password' => 'Password1#',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('tenants'));
});

test('tenant cannot be delete with wrong password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('Password1#'),
    ]);
    $tenant = Tenant::factory()->create();

    $this->actingAs($user);

    $response = $this
        ->actingAs($user)
        ->delete(route('tenants.destroy', $tenant->id), [
            'current_password' => 'Password11#',
        ]);

    $response
        ->assertSessionHasErrors(['current_password']);
});
