<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EmployeeContract\StoreEmployeeContractRequest;
use App\Http\Requests\EmployeeContract\UpdateEmployeeContractRequest;
use App\Http\Resources\EmployeeContractResource;
use App\Models\EmployeeContract;
use Illuminate\Http\Request;

class EmployeeContractController extends ApiController
{
    /**
     * Display a listing of employee contracts.
     */
    public function index(Request $request)
    {
        $query = EmployeeContract::with(['employee', 'site']);

        // Filter by employee
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by site
        if ($request->has('site_id')) {
            $query->where('site_id', $request->site_id);
        }

        // Filter by salary type
        if ($request->has('salary_type')) {
            $query->where('salary_type', $request->salary_type);
        }

        // Filter active contracts only
        if ($request->boolean('active_only')) {
            $now = now()->toDateString();
            $query->where('start_date', '<=', $now)
                  ->where(function ($q) use ($now) {
                      $q->whereNull('end_date')
                        ->orWhere('end_date', '>=', $now);
                  });
        }

        $contracts = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success([
            'contracts' => EmployeeContractResource::collection($contracts),
            'pagination' => [
                'current_page' => $contracts->currentPage(),
                'last_page' => $contracts->lastPage(),
                'per_page' => $contracts->perPage(),
                'total' => $contracts->total(),
            ],
        ]);
    }

    /**
     * Store a newly created contract.
     */
    public function store(StoreEmployeeContractRequest $request)
    {
        try {
            $contract = EmployeeContract::create($request->validated());

            return $this->success(
                new EmployeeContractResource($contract->load(['employee', 'site'])),
                'Contract created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create contract: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified contract.
     */
    public function show(EmployeeContract $employeeContract)
    {
        return $this->success(
            new EmployeeContractResource($employeeContract->load(['employee', 'site'])),
            'Contract retrieved successfully'
        );
    }

    /**
     * Update the specified contract.
     */
    public function update(UpdateEmployeeContractRequest $request, EmployeeContract $employeeContract)
    {
        try {
            $employeeContract->update($request->validated());

            return $this->success(
                new EmployeeContractResource($employeeContract->load(['employee', 'site'])),
                'Contract updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update contract: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified contract.
     */
    public function destroy(EmployeeContract $employeeContract)
    {
        try {
            $employeeContract->delete();

            return $this->success(null, 'Contract deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete contract: ' . $e->getMessage(), 500);
        }
    }
}
