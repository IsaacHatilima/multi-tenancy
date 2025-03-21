<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'escalation' => $this->faker->randomElement(['level1', 'level2', 'level3']),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'complete', 'cancelled']),
            'title' => $this->faker->word(),
            'description' => fake()->text(),
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'assigned_to' => User::factory(),
        ];
    }
}
