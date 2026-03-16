<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_plan_id',
        'client_contract_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'amount',
        'tax_amount',
        'total_amount',
        'status',
        'paid_at',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the invoice plan that owns the invoice.
     */
    public function invoicePlan(): BelongsTo
    {
        return $this->belongsTo(InvoicePlan::class);
    }

    /**
     * Get the client contract that owns the invoice.
     */
    public function clientContract(): BelongsTo
    {
        return $this->belongsTo(ClientContract::class);
    }

    /**
     * Get the attachments for the invoice.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(InvoiceAttachment::class);
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue()
    {
        return $this->status !== 'Paid' && $this->due_date < now();
    }

    /**
     * Scope a query to filter by contract.
     */
    public function scopeForContract($query, $contractId)
    {
        return $query->where('client_contract_id', $contractId);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query for overdue invoices.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'Paid')
                    ->where('due_date', '<', now());
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid($paymentMethod = null)
    {
        $this->update([
            'status' => 'Paid',
            'paid_at' => now(),
            'payment_method' => $paymentMethod,
        ]);
    }

    /**
     * Generate invoice number.
     */
    public static function generateInvoiceNumber()
    {
        $year = now()->year;
        $month = now()->format('m');
        $prefix = "INV-{$year}{$month}";

        $lastInvoice = self::where('invoice_number', 'like', "{$prefix}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if (!$lastInvoice) {
            return "{$prefix}-0001";
        }

        $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$newNumber}";
    }
}
