<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'area_name',
        'surface_area_m2',
        'current_condition_image',
    ];

    protected $casts = [
        'surface_area_m2' => 'float',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
