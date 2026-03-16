<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'area_id',
        'assigned_to_id',
        'title',
        'description',
        'task_type',
        'priority',
        'status',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the area that the task belongs to.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the employee assigned to this task.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to_id');
    }

    /**
     * Get the task logs for this task.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(TaskLog::class);
    }

    /**
     * Check if task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'Completed';
    }

    /**
     * Scope a query to only include tasks by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include tasks by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', 'Completed');
    }
}
