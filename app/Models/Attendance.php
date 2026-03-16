<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'site_id',
        'date',
        'clock_in',
        'clock_out',
        'latitude_in',
        'longitude_in',
        'selfie_path_in',
        'selfie_path_out',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
    ];

    /**
     * Get the employee that owns the attendance.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the site where the attendance occurred.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Calculate total working hours.
     */
    public function getWorkingHoursAttribute(): ?float
    {
        if (!$this->clock_in || !$this->clock_out) {
            return null;
        }

        return $this->clock_in->diffInHours($this->clock_out, true);
    }

    /**
     * Check if clock-in location is within site radius.
     */
    public function isWithinSiteRadius(): bool
    {
        if (!$this->latitude_in || !$this->longitude_in || !$this->site) {
            return false;
        }

        $earthRadius = 6371000; // meters

        $latFrom = deg2rad((float) $this->site->latitude);
        $lonFrom = deg2rad((float) $this->site->longitude);
        $latTo = deg2rad((float) $this->latitude_in);
        $lonTo = deg2rad((float) $this->longitude_in);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance <= $this->site->radius_meters;
    }

    /**
     * Scope a query to only include attendances for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope a query to only include attendances for a specific employee.
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }
}
