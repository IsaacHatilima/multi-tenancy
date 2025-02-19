<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'plan' => 'free',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function configure(): TenantFactory
    {
        return $this->afterCreating(function (Tenant $tenant) {
            $tenant->update([
                'tenancy_db_name' => config('tenancy.database.prefix').$tenant->id,
            ]);

            $tenant->run(function ($tenant) {
                User::factory()->create(['tenant_id' => $tenant->id]);
            });

            $tenant->domains()->create([
                'domain' => 'domain-'.substr($tenant->id, -3),
            ]);
        });
    }
}
