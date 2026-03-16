<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_contract_id',
        'invoice_schedule',
        'amount_per_invoice',
        'tax_percentage',
        'notes',
    ];

    protected $casts = [
        'amount_per_invoice' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
    ];

    /**
     * Get the client contract that owns the invoice plan.
     */
    public function clientContract(): BelongsTo
    {
        return $this->belongsTo(ClientContract::class);
    }

    /**
     * Get the invoices for the invoice plan.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Calculate tax amount.
     */
    public function getTaxAmountAttribute()
    {
        return ($this->amount_per_invoice * $this->tax_percentage) / 100;
    }

    /**
     * Calculate total amount including tax.
     */
    public function getTotalAmountAttribute()
    {
        return $this->amount_per_invoice + $this->tax_amount;
    }

    /**
     * Scope a query to filter by contract.
     */
    public function scopeForContract($query, $contractId)
    {
        return $query->where('client_contract_id', $contractId);
    }
}
