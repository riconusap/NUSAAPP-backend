<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_date',
        'transaction_type',
        'category',
        'amount',
        'reference_type',
        'reference_id',
        'payment_method',
        'description',
        'receipt_path',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the reference model (polymorphic).
     */
    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query for income transactions.
     */
    public function scopeIncome($query)
    {
        return $query->where('transaction_type', 'Income');
    }

    /**
     * Scope a query for expense transactions.
     */
    public function scopeExpense($query)
    {
        return $query->where('transaction_type', 'Expense');
    }

    /**
     * Get income total for a period.
     */
    public static function getTotalIncome($startDate, $endDate)
    {
        return self::income()
            ->dateRange($startDate, $endDate)
            ->sum('amount');
    }

    /**
     * Get expense total for a period.
     */
    public static function getTotalExpense($startDate, $endDate)
    {
        return self::expense()
            ->dateRange($startDate, $endDate)
            ->sum('amount');
    }

    /**
     * Get net income for a period.
     */
    public static function getNetIncome($startDate, $endDate)
    {
        $income = self::getTotalIncome($startDate, $endDate);
        $expense = self::getTotalExpense($startDate, $endDate);

        return $income - $expense;
    }
}
