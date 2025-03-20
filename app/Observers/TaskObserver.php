<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;

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
        $userName = $this->getUserFullName();

        if ($task->isDirty('priority')) {
            $changes[] = 'changed priority to '.ucwords($task->priority->value);
        }

        if ($task->isDirty('status')) {
            $changes[] = 'changed status to '.ucwords(str_replace('_', ' ', $task->status->value));
        }

        if ($task->isDirty('escalation')) {
            $changes[] = 'changed escalation to '.ucwords($task->escalation->value);
        }

        if ($task->isDirty('assigned_to')) {
            $changes[] = 'reassigned task to '.$this->assignedUser($task->assigned_to);
        }

        if (! empty($changes)) {
            TaskLog::create([
                'task_id' => $task->id,
                'user_id' => auth()->id(),
                'action' => 'updated',
                'action_performed' => $userName.' '.implode(', ', $changes),
            ]);
        }
    }

    private function assignedUser($userId): string
    {
        $user = User::with('profile')->firstWhere('id', $userId);

        return "{$user->profile->first_name} {$user->profile->last_name}";
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
            'action' => $task->deleted_at ? 'deleted' : 'restored',
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
