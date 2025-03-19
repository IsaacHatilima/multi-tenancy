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
            'priority' => fake()->word(),
            'escalation' => fake()->word(),
            'title' => fake()->word(),
            'description' => fake()->text(),
            'start' => Carbon::now(),
            'end' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'assigned_to' => User::factory(),
        ];
    }
}
