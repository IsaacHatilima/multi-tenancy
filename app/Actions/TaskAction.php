<?php

namespace App\Actions;

use App\Models\Task;
use App\Models\User;

class TaskAction
{
    public function __construct() {}

    public function get_tasks($request)
    {
        $user = auth()->user();

        $query = Task::query()
            ->with(['assignedTo.profile'])
            ->orderBy('created_at', $request->sorting ?: 'desc');

        if ($user->role === 'admin') {
            $query->withTrashed();
        }

        return $query->paginate(10)->withQueryString();
    }

    public function create($request): void
    {
        Task::create([
            'user_id' => auth()->id(),
            'assigned_to' => $this->get_assigned_user($request->assigned_to),
            'priority' => $request->priority ? strtolower($request->priority) : null,
            'escalation' => $request->escalation ? strtolower($request->escalation) : null,
            'status' => $request->status ? strtolower($request->status) : null,
            'title' => ucwords($request->title),
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
        ]);
    }

    private function get_assigned_user($id)
    {
        return User::firstWhere('id', $id)->id;
    }

    public function update($task, $request): void
    {
        $task->update([
            'user_id' => auth()->id(),
            'assigned_to' => $this->get_assigned_user($request->assigned_to),
            'priority' => $request->priority ? strtolower($request->priority) : null,
            'escalation' => $request->escalation ? strtolower($request->escalation) : null,
            'status' => $request->status ? strtolower($request->status) : null,
            'title' => ucwords($request->title),
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
        ]);
    }

    public function delete($task): void
    {
        $task->delete();
    }

    public function restore($task): void
    {
        $task->restore();
    }
}
