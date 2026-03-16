<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TaskLog\StoreTaskLogRequest;
use App\Http\Resources\TaskLogResource;
use App\Models\TaskLog;
use Illuminate\Http\Request;

class TaskLogController extends ApiController
{
    /**
     * Display a listing of task logs.
     */
    public function index(Request $request)
    {
        $query = TaskLog::query();

        // Filter by task
        if ($request->has('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        // Filter by employee
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Load relationships
        $query->with(['task.area', 'employee']);

        $logs = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'logs' => TaskLogResource::collection($logs),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ],
        ]);
    }

    /**
     * Store a newly created task log.
     */
    public function store(StoreTaskLogRequest $request)
    {
        try {
            $taskLog = TaskLog::create($request->validated());

            return $this->success(
                new TaskLogResource($taskLog->load(['task.area', 'employee'])),
                'Task log created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create task log: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified task log.
     */
    public function show(TaskLog $taskLog)
    {
        return $this->success(
            new TaskLogResource($taskLog->load(['task.area', 'employee']))
        );
    }

    /**
     * Remove the specified task log.
     */
    public function destroy(TaskLog $taskLog)
    {
        try {
            $taskLog->delete();

            return $this->success(null, 'Task log deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete task log: ' . $e->getMessage(), 500);
        }
    }
}
