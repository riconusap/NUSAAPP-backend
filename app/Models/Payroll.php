<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'period_month',
        'period_year',
        'basic_salary',
        'total_days_worked',
        'total_hours_worked',
        'overtime_hours',
        'overtime_pay',
        'allowances',
        'deductions',
        'gross_salary',
        'net_salary',
        'status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'total_hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the employee that owns the payroll.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Scope a query to filter by employee.
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope a query to filter by period.
     */
    public function scopeForPeriod($query, $month, $year)
    {
        return $query->where('period_month', $month)
                    ->where('period_year', $year);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Calculate gross salary.
     */
    public function calculateGrossSalary()
    {
        return $this->basic_salary + $this->overtime_pay + $this->allowances;
    }

    /**
     * Calculate net salary.
     */
    public function calculateNetSalary()
    {
        return $this->calculateGrossSalary() - $this->deductions;
    }

    /**
     * Mark payroll as paid.
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'Paid',
            'paid_at' => now(),
        ]);
    }
}
