<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskLogFactory extends Factory
{
    protected $model = TaskLog::class;

    public function definition(): array
    {
        return [
            'action' => fake()->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'task_id' => Task::factory(),
            'user_id' => User::factory(),
        ];
    }
}
