<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskLog;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'action_performed' => $this->getUserFullName().' created the task.',
        ]);
    }

    private function getUserFullName(): string
    {
        $profile = auth()->user()->profile;

        return "{$profile->first_name} {$profile->last_name}";
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        $changes = [];

        if ($task->isDirty('priority')) {
            $changes[] = $this->getUserFullName().' changed priority to '.ucwords($task->priority);
        }

        if ($task->isDirty('status')) {
            $changes[] = $this->getUserFullName().' changed status to '.ucwords($task->status);
        }

        if ($task->isDirty('escalation')) {
            $changes[] = $this->getUserFullName().' changed escalation to '.ucwords($task->escalation);
        }

        if (! empty($changes)) {
            TaskLog::create([
                'task_id' => $task->id,
                'user_id' => auth()->id(),
                'action' => 'updated',
                'action_performed' => implode(', ', $changes),
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'action_performed' => $this->getUserFullName().' deleted the task',
        ]);
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'action_performed' => $this->getUserFullName().' restored the task',
        ]);
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
