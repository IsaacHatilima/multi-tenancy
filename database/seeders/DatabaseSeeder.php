<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Central database user
        User::factory()->create();

        /*$tenant = Tenant::factory()->create();

        Domain::factory()->create([
            'tenant_id' => $tenant->id,
        ]);*/

    }
}
