<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends ApiController
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                                    ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by employment status
        if ($request->has('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        // Load relationships
        $query->with(['user', 'contracts.site']);

        $employees = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'employees' => EmployeeResource::collection($employees),
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }

    /**
     * Store a newly created employee.
     */
    public function store(StoreEmployeeRequest $request)
    {
        try {
            $employee = Employee::create($request->validated());

            return $this->success(
                new EmployeeResource($employee->load(['user', 'contracts'])),
                'Employee created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create employee: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        try {
            return $this->success(
                new EmployeeResource($employee->load([
                    'user',
                    'contracts.site',
                    'documents'
                ])),
                'Employee retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve employee: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified employee.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        try {
            $employee->update($request->validated());

            return $this->success(
                new EmployeeResource($employee->load(['user', 'contracts'])),
                'Employee updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update employee: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();

            return $this->success(null, 'Employee deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete employee: ' . $e->getMessage(), 500);
        }
    }
}
