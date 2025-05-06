<?php

namespace App\Http\Controllers\api\V1\Task;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the user's tasks.
     */
    public function index()
    {
        return response()->json(
            Auth::user()->tasks()->latest()->get()
        );
    }

    /**
     * Store a newly created task.
     */
    public function store(TaskRequest $request)
    {
        $task = Auth::user()->tasks()->create($request->all());
        return response()->json($task, 201);
    }

    /**
     * Display a specific task (optional).
     */
    public function show(Task $task)
    {
        $this->authorizeTask($task);
        return response()->json($task);
    }

    /**
     * Update the specified task.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $this->authorizeTask($task);
        $task->update($request->all());
        return response()->json($task);
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task)
    {
        $this->authorizeTask($task);

        $task->delete();

        return response()->noContent();
    }

    /**
     * Ensure the authenticated user owns the task.
     */
    private function authorizeTask(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}
