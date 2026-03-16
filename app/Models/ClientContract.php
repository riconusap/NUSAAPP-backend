<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientContract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'contract_number',
        'contract_type',
        'start_date',
        'end_date',
        'total_contract_value',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_contract_value' => 'decimal:2',
    ];

    /**
     * Get the client that owns the contract.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the invoice plans for this contract.
     */
    public function invoicePlans(): HasMany
    {
        return $this->hasMany(InvoicePlan::class, 'client_contract_id');
    }

    /**
     * Get the invoices for this contract.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'client_contract_id');
    }

    /**
     * Check if contract is currently active.
     */
    public function isActive(): bool
    {
        $now = now();
        $isStarted = $this->start_date->lte($now);
        $isNotEnded = is_null($this->end_date) || $this->end_date->gte($now);

        return $isStarted && $isNotEnded;
    }

    /**
     * Scope a query to only include active contracts.
     */
    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }
}
