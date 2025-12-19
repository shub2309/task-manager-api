<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::with('project', 'assignee');

        if ($request->user()->role !== 'ADMIN') {
            $query->where('assigned_to', $request->user()->id);
        }

        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        if ($request->user()->role !== 'ADMIN') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task = Task::create($request->validated());

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if (request()->user()->role !== 'ADMIN' && $task->assigned_to !== request()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $task->load('project', 'assignee');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $user = $request->user();
        $validated = $request->validated();

        // State transition rules regarding OVERDUE
        if ($task->status === 'OVERDUE') {
            if (isset($validated['status']) && $validated['status'] === 'IN_PROGRESS') {
                return response()->json(['message' => 'Overdue tasks cannot be moved back to WIP'], 422);
            }
            // "Only Admins can close overdue tasks" (assuming close means set to DONE)
            if (isset($validated['status']) && $validated['status'] === 'DONE') {
                if ($user->role !== 'ADMIN') {
                    return response()->json(['message' => 'Only Admins can close overdue tasks'], 403);
                }
            }
        }

        if ($user->role === 'ADMIN') {
            $task->update($validated);
        } else {
            if ($task->assigned_to !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            // User can only update status
            // We only take status from validated if present
            if (isset($validated['status'])) {
                $task->update(['status' => $validated['status']]);
            }
        }

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (request()->user()->role !== 'ADMIN') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(null, 204);
    }
}
