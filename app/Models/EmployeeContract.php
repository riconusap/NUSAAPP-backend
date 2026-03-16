<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeContract extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'site_id',
        'internal_contract_number',
        'position',
        'salary_type',
        'base_salary',
        'daily_rate',
        'start_date',
        'end_date',
        'contract_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_salary' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the employee that owns the contract.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the site for this contract.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Check if contract is currently active.
     */
    public function isActive(): bool
    {
        $now = now()->toDateString();
        return $this->start_date <= $now &&
               ($this->end_date === null || $this->end_date >= $now);
    }
}
