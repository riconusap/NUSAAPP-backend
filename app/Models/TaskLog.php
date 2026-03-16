<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'employee_id',
        'activity_note',
        'photo_before',
        'photo_after',
    ];

    /**
     * Get the task that owns the log.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the employee who created the log.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
