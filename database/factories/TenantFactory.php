<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'tenant_number' => 'TN-'.rand(000001, 999999),
            'name' => $name,
            'slug' => Str::slug($name),
            'address' => rand(1, 9).' '.$this->faker->word,
            'city' => $this->faker->city,
            'state' => $this->faker->word,
            'zip' => $this->faker->postcode,
            'country' => $this->faker->country,
            'contact_email' => $this->faker->email,
            'contact_phone' => $this->faker->phoneNumber,
            'contact_name' => $this->faker->firstName.' '.$this->faker->lastName,
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

            Domain::factory()->create(['tenant_id' => $tenant->id]);
        });
    }
}
