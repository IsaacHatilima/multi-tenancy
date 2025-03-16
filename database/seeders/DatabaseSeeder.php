<?php

namespace Database\Seeders;

use App\Models\Tenant;
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
        $user = User::factory()->create([
            'email' => 'admin@mail.com',
        ]);

        $tenantCount = Tenant::count();

        for ($i = 1; $i <= 15; $i++) {

            Tenant::factory()->create([
                'created_by' => $user->id,
                'tenant_number' => 'TN-'.str_pad($tenantCount + $i, 4, '0', STR_PAD_LEFT),
            ]);
        }

    }
}
