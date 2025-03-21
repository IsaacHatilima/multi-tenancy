<?php

use App\Models\Tenant;
use App\Models\User;

function tenantUrl(string $routeName, $domain, array $parameters = []): string
{
    // switches to tenant subdomain
    $baseUrl = preg_replace('#^https?://#', '', route($routeName, $parameters));
    $scheme = parse_url(route($routeName), PHP_URL_SCHEME);
    $path = parse_url(route($routeName, $parameters), PHP_URL_PATH);

    // $baseUrl = multi-tenancy.test

    // $domain = subdomain

    // dd($path);

    return $scheme.'://'.$domain.'.'.$baseUrl;
}

function createTenant($centralUser, $userType)
{
    $tenants = Tenant::count();
    $tenantNumber = 'TN-'.str_pad($tenants + 1, 4, '0', STR_PAD_LEFT);

    $tenant = Tenant::factory()->create(['tenant_number' => $tenantNumber, 'created_by' => $centralUser->id]);

    if ($userType != 'central') {
        $tenant->run(function ($tenant) {
            User::create([
                'tenant_id' => $tenant->id,
                'email' => 'tenant@mail.com',
                'password' => Hash::make('Password1#'),
            ])->profile()->create([
                'first_name' => 'james',
                'last_name' => 'peters',
            ]);
        });

        tenancy()->initialize($tenant);
    }

    return $tenant;
}
