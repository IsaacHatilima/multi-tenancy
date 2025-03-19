<?php

namespace App\Actions;

use App\Models\Task;
use App\Models\User;

class TaskAction
{
    public function __construct() {}

    public function create($request): void
    {
        Task::create([
            'user_id' => auth()->id(),
            'assigned_to' => $this->get_assigned_user($request->assigned_to),
            'priority' => $request->priority,
            'escalation' => $request->escalation,
            'status' => $request->status,
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
}
