<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'headquarter_address',
        'pic_name',
        'pic_phone',
    ];

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(ClientContract::class);
    }
}
