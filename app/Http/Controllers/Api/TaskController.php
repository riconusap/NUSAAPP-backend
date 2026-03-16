<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\AssignTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends ApiController
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        $query = Task::query();

        // Filter by area
        if ($request->has('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        // Filter by assigned employee
        if ($request->has('assigned_to_id')) {
            $query->where('assigned_to_id', $request->assigned_to_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->byPriority($request->priority);
        }

        // Filter by task type
        if ($request->has('task_type')) {
            $query->where('task_type', $request->task_type);
        }

        // Filter overdue tasks
        if ($request->has('overdue') && $request->overdue) {
            $query->overdue();
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Load relationships
        $query->with(['area.site.client', 'assignedTo', 'logs']);

        $tasks = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'tasks' => TaskResource::collection($tasks),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ],
        ]);
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $task = Task::create($request->validated());

            return $this->success(
                new TaskResource($task->load(['area.site.client', 'assignedTo', 'logs'])),
                'Task created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create task: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        return $this->success(
            new TaskResource($task->load(['area.site.client', 'assignedTo', 'logs.employee']))
        );
    }

    /**
     * Update the specified task.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $task->update($request->validated());

            return $this->success(
                new TaskResource($task->load(['area.site.client', 'assignedTo', 'logs'])),
                'Task updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update task: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();

            return $this->success(null, 'Task deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete task: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Assign task to an employee.
     */
    public function assign(AssignTaskRequest $request, Task $task)
    {
        try {
            $task->update([
                'assigned_to_id' => $request->assigned_to_id,
            ]);

            return $this->success(
                new TaskResource($task->load(['area.site.client', 'assignedTo'])),
                'Task assigned successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to assign task: ' . $e->getMessage(), 500);
        }
    }
}
