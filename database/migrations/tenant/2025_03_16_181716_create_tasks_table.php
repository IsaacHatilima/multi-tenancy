<?php

use App\Enums\TaskEscalation;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('assigned_to')->constrained('users');
            $table->enum('priority', array_column(TaskPriority::cases(), 'value'))->default(TaskPriority::LOW->value);
            $table->enum('escalation', array_column(TaskEscalation::cases(), 'value'))->nullable();
            $table->enum('status', array_column(TaskStatus::cases(), 'value'))->default(TaskStatus::PENDING->value);
            $table->string('title');
            $table->longText('description');
            $table->timestamp('start');
            $table->timestamp('end');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
