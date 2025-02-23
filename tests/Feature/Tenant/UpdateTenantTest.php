<?php

use App\Models\Tenant;
use App\Models\User;

test('tenant can be updated', function () {
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();

    $this->actingAs($user);

    $response = $this
        ->actingAs($user)
        ->put(route('tenants.update', $tenant->id), [
            'name' => 'new name',
            'address' => 'street 3',
            'city' => 'city',
            'state' => 'state',
            'country' => 'wakanda',
            'zip' => '12345',
            'contact_name' => 'John Doe',
            'contact_email' => 'john.doe@gmail.com',
            'contact_phone' => '123497541',
        ]);

    $tenant->refresh();

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('tenants.update', $tenant->slug));

    $this->assertSame('NEW NAME', $tenant->name);
    $this->assertSame('John Doe', $tenant->contact_name);
    $this->assertSame('john.doe@gmail.com', $tenant->contact_email);

});

test('tenant cannot be updated', function () {
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();

    $this->actingAs($user);

    $response = $this
        ->actingAs($user)
        ->put(route('tenants.update', $tenant->id), [
            'name' => '',
            'address' => 'street 3',
            'city' => 'city',
            'state' => 'state',
            'country' => 'wakanda',
            'zip' => '12345',
            'contact_name' => 'John Doe',
            'contact_email' => 'john.doe@gmail.com',
            'contact_phone' => '123497541',
        ]);

    $tenant->refresh();

    $response->assertSessionHasErrors(['name']);
});
