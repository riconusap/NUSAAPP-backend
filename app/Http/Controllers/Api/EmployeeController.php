<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $data = $request->validated();

            $employee = DB::transaction(function () use ($data) {
                $siteId = (int) $data['site_id'];
                unset($data['site_id']);

                $data['nip'] = $this->generateNextNip($siteId);

                return Employee::create($data);
            });

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
     * Generate next NIP using NIP{SITE_ID_PADDED}YY#### format.
     */
    private function generateNextNip(int $siteId): string
    {
        $siteCode = str_pad((string) $siteId, 3, '0', STR_PAD_LEFT);
        $prefix = 'NIP' . $siteCode . now()->format('y');

        $lastEmployee = Employee::where('nip', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('nip')
            ->first();

        $lastSequence = $lastEmployee ? (int) substr($lastEmployee->nip, -4) : 0;
        $nextSequence = $lastSequence + 1;

        if ($nextSequence > 9999) {
            throw new \RuntimeException('NIP sequence limit reached for this year');
        }

        return $prefix . str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
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
