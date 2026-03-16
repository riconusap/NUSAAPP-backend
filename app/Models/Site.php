<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'site_name',
        'address',
        'latitude',
        'longitude',
        'radius_meters',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius_meters' => 'float',
    ];

    /**
     * Get the client that owns the site.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the areas for the site.
     */
    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    /**
     * Get the employee contracts for the site.
     */
    public function employeeContracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class);
    }

    /**
     * Alias for employeeContracts (used in controllers).
     */
    public function contracts(): HasMany
    {
        return $this->employeeContracts();
    }

    /**
     * Get the site inventories.
     */
    public function siteInventories(): HasMany
    {
        return $this->hasMany(SiteInventory::class);
    }

    /**
     * Get the attendances for the site.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
