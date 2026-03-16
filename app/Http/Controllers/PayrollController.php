<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payroll\StorePayrollRequest;
use App\Http\Requests\Payroll\UpdatePayrollRequest;
use App\Http\Requests\Payroll\GeneratePayrollRequest;
use App\Http\Resources\PayrollResource;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\EmployeeContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view_payrolls');

        $query = Payroll::with('employee');

        // Filter by employee
        if ($request->has('employee_id')) {
            $query->forEmployee($request->employee_id);
        }

        // Filter by period
        if ($request->has('period_month')) {
            $query->where('period_month', $request->period_month);
        }

        if ($request->has('period_year')) {
            $query->where('period_year', $request->period_year);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        $payrolls = $query->orderBy('period_year', 'desc')
            ->orderBy('period_month', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => PayrollResource::collection($payrolls),
            'pagination' => [
                'current_page' => $payrolls->currentPage(),
                'per_page' => $payrolls->perPage(),
                'total' => $payrolls->total(),
                'last_page' => $payrolls->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePayrollRequest $request)
    {
        $payroll = Payroll::create($request->validated());

        // Calculate and update salary amounts
        $payroll->gross_salary = $payroll->calculateGrossSalary();
        $payroll->net_salary = $payroll->calculateNetSalary();
        $payroll->save();

        return response()->json([
            'success' => true,
            'message' => 'Payroll created successfully',
            'data' => new PayrollResource($payroll->load('employee')),
        ], 201);
    }

    /**
     * Generate payroll automatically from attendance data.
     */
    public function generate(GeneratePayrollRequest $request)
    {
        try {
            DB::beginTransaction();

            $employee = $request->employee_id;
            $month = $request->period_month;
            $year = $request->period_year;

            // Check if payroll already exists
            $exists = Payroll::forEmployee($employee)
                ->forPeriod($month, $year)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payroll for this period already exists',
                ], 422);
            }

            // Get active employee contract
            $contract = EmployeeContract::where('employee_id', $employee)
                ->where('start_date', '<=', now())
                ->where(function ($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->first();

            if (!$contract) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active contract found for employee',
                ], 422);
            }

            // Get attendance data for the period
            $startDate = "{$year}-{$month}-01";
            $endDate = date('Y-m-t', strtotime($startDate));

            $attendances = Attendance::where('employee_id', $employee)
                ->whereBetween('date', [$startDate, $endDate])
                ->whereNotNull('clock_out')
                ->get();

            $totalDays = $attendances->count();
            $totalHours = $attendances->sum('working_hours');

            // Calculate overtime (hours exceeding 8 hours per day)
            $overtimeHours = 0;
            foreach ($attendances as $attendance) {
                if ($attendance->working_hours > 8) {
                    $overtimeHours += ($attendance->working_hours - 8);
                }
            }

            // Calculate pay
            $basicSalary = $contract->base_salary;
            $overtimePay = $overtimeHours * ($contract->base_salary / 173); // 173 = average working hours per month
            $allowances = $request->allowances ?? 0;
            $deductions = $request->deductions ?? 0;

            $payroll = Payroll::create([
                'employee_id' => $employee,
                'period_month' => $month,
                'period_year' => $year,
                'basic_salary' => $basicSalary,
                'total_days_worked' => $totalDays,
                'total_hours_worked' => round($totalHours, 2),
                'overtime_hours' => round($overtimeHours, 2),
                'overtime_pay' => round($overtimePay, 2),
                'allowances' => $allowances,
                'deductions' => $deductions,
                'status' => 'Draft',
                'notes' => $request->notes,
            ]);

            $payroll->gross_salary = $payroll->calculateGrossSalary();
            $payroll->net_salary = $payroll->calculateNetSalary();
            $payroll->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payroll generated successfully',
                'data' => new PayrollResource($payroll->load('employee')),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate payroll: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payroll $payroll)
    {
        $this->authorize('view_payrolls');

        return response()->json([
            'success' => true,
            'data' => new PayrollResource($payroll->load('employee')),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayrollRequest $request, Payroll $payroll)
    {
        $payroll->update($request->validated());

        // Recalculate salary amounts
        $payroll->gross_salary = $payroll->calculateGrossSalary();
        $payroll->net_salary = $payroll->calculateNetSalary();
        $payroll->save();

        return response()->json([
            'success' => true,
            'message' => 'Payroll updated successfully',
            'data' => new PayrollResource($payroll->load('employee')),
        ]);
    }

    /**
     * Mark payroll as paid.
     */
    public function markAsPaid(Payroll $payroll)
    {
        $this->authorize('edit_payrolls');

        if ($payroll->status === 'Paid') {
            return response()->json([
                'success' => false,
                'message' => 'Payroll already marked as paid',
            ], 422);
        }

        $payroll->markAsPaid();

        return response()->json([
            'success' => true,
            'message' => 'Payroll marked as paid successfully',
            'data' => new PayrollResource($payroll->load('employee')),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        $this->authorize('delete_payrolls');

        $payroll->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payroll deleted successfully',
        ]);
    }
}
