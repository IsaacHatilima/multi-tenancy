<?php

namespace App\Http\Controllers;

use App\Actions\TaskAction;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    use AuthorizesRequests;

    private TaskAction $taskAction;

    public function __construct(TaskAction $taskAction)
    {
        $this->taskAction = $taskAction;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', [User::class, tenant()]);

        return Inertia::render('Tasks/Index', [
            'tasks' => $this->taskAction->get_tasks($request),
            'users' => User::with(['profile:id,user_id,first_name,last_name'])
                ->select('id')
                ->get(),
            'filters' => [
                'sorting' => $request->sorting,
            ],
        ]);
    }

    public function store(TaskRequest $request)
    {
        $this->authorize('create', [User::class, tenant()]);

        $this->taskAction->create($request);

        return redirect()->back();
    }

    public function show($taskId)
    {
        $task = Task::withTrashed()->with('assignedTo.profile')->findOrFail($taskId);
        $this->authorize('view', [$task, tenant()]);

        return Inertia::render('Tasks/Partials/TaskDetails', [
            'task' => $task->load('assignedTo.profile'),
            'taskLogs' => TaskLog::where('task_id', $task->id)->get(),
            'users' => User::with(['profile:id,user_id,first_name,last_name'])
                ->select('id')
                ->get(),
        ]);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', [$task, tenant()]);

        $this->taskAction->update($task, $request);

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', [$task, tenant()]);

        $this->taskAction->delete($task);

        return redirect()->route('tasks.index');
    }

    public function restore($taskId)
    {
        $task = Task::withTrashed()->findOrFail($taskId);

        $this->authorize('restore', [$task, tenant()]);

        $this->taskAction->restore($task);

        return redirect()->back();
    }
}
