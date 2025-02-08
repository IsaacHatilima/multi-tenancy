<?php

use App\Models\User;

test('tenants page can be accessed only from the central domain', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(config('app.url').'/tenants');
    $response->assertStatus(200);
});



test('tenants page is forbidden on a tenant domain', function () {
    $user = User::factory()->create();

    // Act as the user
    $this->actingAs($user);

    // Simulate request to the tenant domain '/tenants' route
    $tenantUrl = 'http://sub.' . parse_url(config('app.url'), PHP_URL_HOST) . '/tenants';

    // Request to the tenant domain '/tenants' route
    $response = $this->get($tenantUrl);
    $response->assertStatus(404);  // Should be blocked on tenant domain
});





