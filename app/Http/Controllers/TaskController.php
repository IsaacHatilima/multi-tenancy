<?php

namespace App\Http\Controllers;

use App\Actions\TaskAction;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class TaskController extends Controller
{
    use AuthorizesRequests;

    private TaskAction $taskAction;

    public function __construct(TaskAction $taskAction)
    {
        $this->taskAction = $taskAction;
    }

    public function index()
    {
        $this->authorize('viewAny', Task::class);

        return Inertia::render('Tasks/Index', [
            'tasks' => Task::with('assignedTo.profile')->paginate(10),
            'users' => User::with(['profile:id,user_id,first_name,last_name'])
                ->select('id')
                ->get(),
        ]);
    }

    public function store(TaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $this->taskAction->create($request);

        return redirect()->back();
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return $task;
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return $task;
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json();
    }
}
