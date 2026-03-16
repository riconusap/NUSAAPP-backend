<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik',
        'nip',
        'full_name',
        'profile_picture_path',
        'phone_number',
        'email',
        'current_address',
        'birth_date',
        'employment_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Get the user account associated with the employee.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    /**
     * Get the employee contracts.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class);
    }

    /**
     * Get the employee documents.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    /**
     * Get the attendances.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the payrolls.
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    /**
     * Get the leave requests.
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Get the tasks assigned to the employee.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to_id');
    }

    /**
     * Get the task logs.
     */
    public function taskLogs(): HasMany
    {
        return $this->hasMany(TaskLog::class);
    }
}
