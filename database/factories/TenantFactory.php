<?php

namespace Database\Factories;

use App\Models\Domain;
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
            'name' => strtoupper($this->faker->unique()->words(rand(1, 3), true)),
            'address' => $this->faker->streetName().' '.$this->faker->numberBetween(1, 999).strtoupper($this->faker->optional(0.5)->randomLetter()),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zip' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'contact_email' => $this->faker->unique()->email(),
            'contact_phone' => '+49'.$this->faker->unique()->numerify('###########'),
            'contact_first_name' => $this->faker->firstName(),
            'contact_last_name' => $this->faker->lastName(),
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
